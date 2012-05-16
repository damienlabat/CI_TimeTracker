<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Timetracker_lib
{
    protected $user_id=NULL;

    function __construct()
    {
        $this->ci =& get_instance();

        $this->ci->load->library('tank_auth');
        $this->user_id= $this->ci->tank_auth->get_user_id();
        $this->user_name= $this->ci->tank_auth->get_username();

        $this->ci->load->model('timetracker/tt_categories');
        $this->ci->load->model('timetracker/tt_activities');
        $this->ci->load->model('timetracker/tt_tags');
        $this->ci->load->model('timetracker/tt_values');
        $this->ci->load->model('timetracker/tt_records');

        $this->ci->load->helper('array');


    }

/* gestion POST */

    function fromPOST($post){
        $res=NULL;


        $this->ci->load->helper('form');
        $this->ci->load->library('form_validation');

        if (element('start',$post)) $res=$this->start_record($post);

        return $res;
        }



    function start_record($post){


            $this->ci->form_validation->set_rules('start', 'Activity', 'required');

            if ($this->ci->form_validation->run() === TRUE) {


                $res=array();
                $param=array();
                $post['start']=trim($post['start']);

                $type_record='activity';

                if ($post['start'][0]=='!') $type_record='todo';
                if ($post['start'][0]=='.') $param['running']=0;  // ping
                if (element('value_name',$post)) {  $type_record='value';    $param['running']=0;   }






                preg_match('/\[{1}.+\]{1}/i',$post['start'], $path_tags); // get tags from path
                if (($path_tags) && (!element('tags',$post))) $post['tags']=trim($path_tags[0],'[] ');

                 if (element('tags',$post)) $tags=preg_split('/,/', $post['tags'], -1, PREG_SPLIT_NO_EMPTY); // get tags from input




                 $post['start']=preg_replace('/(\!|\.|\[{1}.+\]{1})*/i', '', $post['start']); // clean activity path phase1




                 if ($type_record!='value') {
                     preg_match('/\#{1}.+\={1}.+/i', $post['start'],  $path_value); // get value from path
                     if ($path_value) {
                         $path_value_array=preg_split('/=/', $path_value[0], -1, PREG_SPLIT_NO_EMPTY);
                         $post['value_name']=trim($path_value_array[0],'# ');
                         $post['value']=trim($path_value_array[1]);
                         $type_record='value';    $param['running']=0;
                        }
                 }


                 $post['start']=preg_replace('/\#{1}.+\={1}.+/i', '', $post['start']); // clean activity path phase2





                if (strpos($post['start'], '@') === FALSE)
                {
                    $path = '';
                    $title = trim( $post['start'] );
                }
                else
                {
                    $split= preg_split('/@/', $post['start'], -1, PREG_SPLIT_NO_EMPTY);
                    $path =  trim( $split[1] );
                    $title = trim( $split[0] );
                }



                if (isset($post['description'])) $param['description']=trim( $post['description'] );
                if (isset($post['localtime'])) $param['diff_greenwich']=$post['localtime']; // TODO recup greenwich from time



                $res['activity']= $this->create_record($title,$path,$type_record,$param);
                $res['alerts']= array( array('type'=>'success', 'alert'=>'start new activity: '.$res['activity']['title']) );

                if (isset($tags))
                     foreach ($tags as $k => $tag)
                                $this->add_tag( $res['activity']['record']['id'], trim($tag) );  // add tags

                if (element('value_name',$post))
                    $this->add_value( $res['activity']['record']['id'], trim($post['value_name']), trim($post['value']) ); // add value
            }
         return $res;
        }




    function build_breadcrumb($obj)
    {
        $breadcrumb=array();

        if (isset($obj['start_time']))    $breadcrumb[]= array('title'=>$obj['start_time'], 'url'=>'');
            else if ( (isset($obj['id'])) && (isset($obj['categorie_ID'])) )  $obj['activity_ID']=$obj['id'];
        if (isset($obj['activity_ID']))   $breadcrumb[]= array('title'=>$obj['title'], 'url'=>'tt/'.$this->user_name.'/'.$obj['type_of_record'].'/'.$obj['activity_ID']);
        $path_array=array_reverse($obj['path_array']);
        foreach ($path_array as $k => $cat)  $breadcrumb[]= array('title'=>$cat['title'], 'url'=>'tt/'.$this->user_name.'/categorie/'.$cat['id']);

        return array_reverse($breadcrumb);
    }



/* CATEGORIES */

    function getorcreate_categoriespath($path)
    {
        $cat_array=preg_split("/\//", $path);
        $parent=NULL;

        foreach ($cat_array as $k => $cat_title)
        {
            $res=$this->ci->tt_categories->getorcreate_categorie($this->user_id, $cat_title, $parent);
            $parent= $res['id'];
        }

        return $res;
    }


    function get_categories_tree()
    {
        $categories=$this->ci->tt_categories->get_categories_with_count($this->user_id);

        return  $this->recur_tree($categories,'parent',NULL);
    }



    function get_categories_path($forceshow=FALSE)
    {
        $res=array();
        $categories=$this->ci->tt_categories->get_categories($this->user_id);
        foreach ($categories as $k => $item)
        {
            if ( ($forceshow OR $item['show']) && ( isset($res[ $item['parent'] ]) OR !$item['parent']) )
            {
                if ($item['parent']) $res[ $item['id'] ]= $res[ $item['parent'] ].'/'.$item['title'];
                    else $res[ $item['id'] ]= $item['title'];
            }
        }

        sort($res);
        return $res;
    }



    function get_categorie_path_array($categorie_id)
    {
        $current_categorie= $this->ci->tt_categories->get_categorie_by_id($categorie_id);
        $path_array= array($current_categorie);

        while (isset($current_categorie['parent'])) {
                $current_categorie= $this->ci->tt_categories->get_categorie_by_id( $current_categorie['parent'] );
                $path_array[]= $current_categorie;
            }

        return array_reverse($path_array);
    }


    function get_categorie_from_path($path)
    {
        $cat_array=preg_split("/\//", $path);
        $parent=NULL;
        $res=NULL;

        foreach ($cat_array as $k => $cat_title) {
                $res= $this->ci->tt_categories->get_categorie_by_title($this->user_id, $cat_title, $parent);
                $parent=$res['id'];
            }
        return $res;
    }




    function recur_tree($data,$var_root,$root){
        $res=array();
        if ($data)
        foreach ($data as $k => $item)
            if ($item[$var_root] == $root)
            {
                 $sub= $this->recur_tree($data,$var_root,$item['id']);
                 if ($sub)  $item['sub']=$sub;
                 $res[]=$item;
             }
        if (count($res)>0) return $res;
        return NULL;
    }



    function update_categorie($path,$data){
        $cat=$this->getorcreate_categoriespath($path);
        return $this->ci->tt_categories->update_categorie($this->user_id, $cat['title'],$cat['parent'],$data);
    }



    function remove_emptycategories(){
        // TODO!
    }


    // TODO! shared categorie gestion



/* ACTIVITIES */

    function get_categorie_activities( $categorie_id, $show_empty= FALSE )
    {
        return $this->ci->tt_activities->get_categorie_activities( $categorie_id , $show_empty);
    }


    function create_record($title,$path=NULL,$type_record,$param=array())
    {
        $cat=$this->getorcreate_categoriespath($path); // BUG ?
        if (isset($param['tags']))
        {
            $tags=$param['tags'];
            unset($param['tags']);
        }

        if (isset($param['values']))
        {
            $values=$param['values'];
            unset($param['values']);
        }

        $activity=$this->ci->tt_activities->getorcreate_activity($cat['id'], $title, $type_record);

        $activity['record']=$this->ci->tt_records->create_record($activity['id'],$param);

        return $activity;
    }


    function get_running_activities(){
        $activities= $this->ci->tt_records->get_running_activities($this->user_id);
        if ($activities) $activities= $this->complete_records_info($activities);

        return $activities;
    }


    function get_running_TODO(){
        $activities= $this->ci->tt_records->get_running_TODO($this->user_id);
        if ($activities) $activities= $this->complete_records_info($activities);

        return $activities;
    }


    function get_record_by_id($record_id){
        $activitie= $this->ci->tt_records->get_record_by_id($record_id);
        if ($activitie) $activitie= $this->complete_record_info($activitie);

        return $activitie;
    }





    function get_last_actions($categorie_id=NULL, $offset=0,$count=10){
        $records=$this->ci->tt_records->get_last_activities($this->user_id,$offset,$count);
        if ($records) $records= $this->complete_records_info($records);

        return $records;
    }


    function complete_records_info($records) {

        foreach ($records as $k => $record)
            $records[$k]=$this->complete_record_info($record);

        return $records;
    }




    function complete_record_info($record) {

        $record['path_array']= $this->get_categorie_path_array( $record['categorie_ID'] );

            if ($record['running']) $record['duration']= $this->calcul_duration($record);
                else $record['stop_at']= date ("Y-m-d H:i:s",  strtotime( $record['start_time'])+$record['duration'] );

            $record['tags']=$this->ci->tt_tags->get_record_tags($record['id']);
            $record['value']=$this->ci->tt_values->get_record_value($record['id']);

        return $record;
    }


    function delete_record( $record_id )
    {
        return $this->ci->tt_records->delete_record( $record_id );
    }



    function restart_record( $record_id )
    {
        $record=$this->get_record_by_id($record_id);
        $param=array(
            'description'=> $record['description'],
            'diff_greenwich'=>$record['diff_greenwich']
        );

        if (($record['type_of_record']=='value') || ((!$record['running']) && ($record['duration']==0)) ) $param['running']=0;

       $new_record= $this->ci->tt_records->create_record($record['activity_ID'],$param);

       foreach ($record['tags'] as $k => $tag) $this->ci->tt_tags->add_tag( $new_record['id'], $tag['id'] );  // add tags

       if ($record['type_of_record']=='value') $this->ci->tt_values->add_value( $new_record['id'], $record['value']['value_type_ID'], $record['value']['value'] );

        return TRUE;
    }



/* STOP activitie */

function calcul_duration($record, $endtime=NULL )
{
    if ($endtime==NULL) $endtime= time();
    $duration= $endtime - strtotime( $record['start_time'] );
    return $duration;
}

function stop_record($id){
    $record= $this->ci->tt_records->get_record_by_id($id);
    $duration= $this->calcul_duration($record);
    return $this->ci->tt_records->update_record( $id, array('duration'=>$duration, 'running'=>0) );
    }



/* TAGS */

    function add_tag($record_id,$tag)
    {
        $tag_obj=$this->ci->tt_tags->getorcreate_tag( $this->user_id,$tag );
        if ($this->ci->tt_tags->add_tag( $record_id,$tag_obj['id'] ))
            return $tag_obj;
        return NULL;
    }

    function remove_tag($record_id,$tag)
    {
        $tag_obj=$this->ci->tt_tags->getorcreate_tag( $this->user_id,$tag );
        return $this->ci->tt_tags->remove_tag( $record_id,$tag_obj['id'] );

    }

    function update_tag($tag,$param)
    {
        return $this->ci->tt_tags->update_tag( $this->user_id,$tag,$param );

    }

    function get_tag_list(){
        return $this->ci->tt_tags->get_tag_list( $this->user_id );
    }


/* VALUES */

    function add_value($record_id,$value_name,$value)
    {
        $value_obj=$this->ci->tt_values->getorcreate_value_type( $this->user_id, $value_name );
        if ($this->ci->tt_values->add_value( $record_id,$value_obj['id'] ,$value ))
            return $this->ci->tt_values->get_value( $record_id, $value_obj['id']  );
        return NULL;
    }

    function remove_value($record_id,$value_name)
    {
        $value_obj=$this->ci->tt_values->getorcreate_value_type( $this->user_id,$tag , $value_name );
        return $this->ci->tt_values->remove_value( $record_id, $value_obj['id'] );

    }

    function update_value($record_id,$value_name,$value)
    {
        return $this->ci->tt_values->update_value( $this->user_id,$record_id,$value_name,$value );

    }

    function update_value_type($value_name,$param)
    {
        return $this->ci->tt_values->update_value_type( $this->user_id,$value_name,$param );

    }

    function get_value_type_list_list(){
        return $this->ci->tt_values->get_value_type_list( $this->user_id );
    }

    function get_value($record_id){
         return $this->ci->tt_values->get_value( $record_id );
    }

}

/* End of file timetracker.php */
/* Location: ./application/libraries/timetracker.php */