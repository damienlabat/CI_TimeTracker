<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Timetracker_lib
{
    protected $user_id=NULL;

    function __construct()
    {
        $this->ci =& get_instance();

        $this->ci->load->library('tank_auth');
        $this->user_id= $this->ci->tank_auth->get_user_id();

        $this->ci->load->model('timetracker/tt_categories');
        $this->ci->load->model('timetracker/tt_activities');
        $this->ci->load->model('timetracker/tt_tags');
        $this->ci->load->model('timetracker/tt_values');

        $this->ci->load->helper('array');


    }

/* gestion POST */

function fromPOST($post){

    if (element('activity',$post)){
        $param=array();

        if (strpos($post['activity'], '@') === FALSE)
        {
            $path = '';
            $title = trim( $post['activity'] );
        }
        else
        {
            $split= preg_split('/@/', $post['activity'], -1, PREG_SPLIT_NO_EMPTY);
            $path =  trim( $split[1] );
            $title = trim( $split[0] );
        }

        if (element('tags',$post)) $param['tags']=preg_split('/,/', $post['tags'], -1, PREG_SPLIT_NO_EMPTY);

        if (isset($post['description'])) $param['description']=trim( $post['description'] );
        if (isset($post['localtime'])) $param['start_LOCAL']=$post['localtime'];


        $res= $this->create_activity($title,$path,$param);
        }

    }





/* CATEGORIES */

    function getorcreate_categoriespath($path)
    {
        $cat_array=preg_split("/\//", $path);
        $parent=NULL;

        foreach ($cat_array as $k => $cat_title)
        {
            $res=$this->ci->tt_categories->getorcreate_categorie($this->user_id, $cat_title,$parent);
            $parent= $res['id'];
        }

        return $res;
    }


    function get_categories_tree()
    {
        $categories=$this->ci->tt_categories->get_categories($this->user_id);

        return  $this->recur_tree($categories,'parent',NULL);
    }



    function get_categories_path($forceshow=FALSE)
    {
        $res=array();
        $categories=$this->ci->tt_categories->get_categories($this->user_id);
        foreach ($categories as $k => $item)
        {
            //var_dump( ($forceshow OR $item['show']) && ( isset($res[ $item['parent'] ]) OR !$item['parent']) );
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

    function create_activity($title,$path=NULL,$param=array())
    {
        $cat=$this->getorcreate_categoriespath($path);

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

        $activity=$this->ci->tt_activities->create_activity($cat['id'], $title, $param);

        if (isset($tags))
        {
            $activity['tag']=array();
            foreach ($tags as $k => $tag)
                $activity['tag'][]=$this->add_tag($activity['id'], trim($tag) );
        }

        // TODO! ajouter values

        return $activity;
    }


    function get_running_activities(){
        $activities= $this->ci->tt_activities->get_running_activities($this->user_id);
        $activities= $this->complete_activities_info($activities);

        return $activities;
    }


    function get_last_activities($categorie_id=NULL, $offset=0,$count=10){
        $activities=$this->ci->tt_activities->get_last_activities($this->user_id,$offset,$count);
        $activities= $this->complete_activities_info($activities);

        return $activities;
    }


    function complete_activities_info($activities) {

        foreach ($activities as $k => $activity)
        {
            $activities[$k]['path_array']= $this->get_categorie_path_array( $activity['categorie_ID'] );

            if ($activity['running']) $activities[$k]['duration']= $this->calcul_duration($activity);
        }

        return $activities;
    }

/* STOP activitie */

function calcul_duration($activity, $endtime=NULL )
{
    if ($endtime==NULL) $endtime= time();
    $duration= $endtime - strtotime( $activity['start_UNIX'] );
    return $duration;
}

function stop_activity($id){
    $activity= $this->ci->tt_activities->get_activity_by_id($id);
    $duration= $this->calcul_duration($activity);
    return $this->ci->tt_activities->update_activity( $id, array('duration'=>$duration, 'running'=>0) );
    }



/* TAGS */

    function add_tag($activity_id,$tag)
    {
        $tag_obj=$this->ci->tt_tags->getorcreate_tag( $this->user_id,$tag );
        if ($this->ci->tt_tags->add_tag( $activity_id,$tag_obj['id'] ))
            return $tag_obj;
        return NULL;
    }

    function remove_tag($activity_id,$tag)
    {
        $tag_obj=$this->ci->tt_tags->getorcreate_tag( $this->user_id,$tag );
        return $this->ci->tt_tags->remove_tag( $activity_id,$tag_obj['id'] );

    }

    function update_tag($tag,$param)
    {
        return $this->ci->tt_tags->update_tag( $this->user_id,$tag,$param );

    }

    function get_tag_list(){
        return $this->ci->tt_tags->get_tag_list( $this->user_id );
    }


    function get_activity_tags($activity_id){

    }

/* VALUES */

    function add_value($activity_id,$value_name,$value)
    {
        $value_obj=$this->ci->tt_values->getorcreate_value_type( $this->user_id, $value_name );
        if ($this->ci->tt_values->add_value( $activity_id,$value_obj['id'] ,$value ))
            return $this->ci->tt_values->get_value( $activity_id, $value_obj['id']  );
        return NULL;
    }

    function remove_value($activity_id,$value_name)
    {
        $value_obj=$this->ci->tt_values->getorcreate_value_type( $this->user_id,$tag , $value_name );
        return $this->ci->tt_values->remove_value( $activity_id, $value_obj['id'] );

    }

    function update_value($activity_id,$value_name,$value)
    {
        return $this->ci->tt_tags->update_tag( $this->user_id,$tag,$param );

    }

    function update_value_type($value_name,$param)
    {
        return $this->ci->tt_tags->update_tag( $this->user_id,$tag,$param );

    }

    function get_value_type_list_list(){
        return $this->ci->tt_values->get_value_type_list( $this->user_id );
    }

    function get_value_tags($activity_id){

    }

}

/* End of file timetracker.php */
/* Location: ./application/libraries/timetracker.php */