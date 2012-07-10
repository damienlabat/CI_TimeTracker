<?php
if ( !defined( 'BASEPATH' ) )
    exit( 'No direct script access allowed' );

class Timetracker_viz extends CI_Controller {
    function __construct( ) {
        parent::__construct();

        $this->load->library( 'timetracker_lib' );

        $this->timetracker_lib->checkuser();
        $this->timetracker_lib->get_alerts();

    }





    /* ==========================
     *  actions
     * ========================== */


    public function summary( $username = NULL, $type_cat = 'categorie', $id = NULL ) {

        // TODO gestion date bugue

        $this->timetracker_lib->checkUsername( $username );
        
        $count = $this->config->item('headerbloc_perpage');
        $this->data[ 'last_activities' ] =  $this->records->get_records_full($this->user_id, array( 'type_of_record' => 'activity', 'running' => 0 ) ,0 , $count );

        $this->data['current']['action']=       'summary';
        $this->data['current']['cat']=          $type_cat;
        $this->data['current']['id']=           $id;
        $this->timetracker_lib->getCurrentObj();

        $list=array();

        if ( !in_array( $type_cat, array('activity','todo','value') ) ) {
            $list[ 'count' ][ 'activity' ]    = $this->records->get_records_count($this->user_id, array( 'categorie'=>$id, 'type_of_record' => 'activity',    'datefrom' => $this->data[ 'current' ]['datefrom'], 'dateto' => $this->data[ 'current' ]['dateto'] ) );
            $list[ 'count' ][ 'todo' ]        = $this->records->get_records_count($this->user_id, array( 'categorie'=>$id, 'type_of_record' => 'todo',        'datefrom' => $this->data[ 'current' ]['datefrom'], 'dateto' => $this->data[ 'current' ]['dateto'] ) );
            $list[ 'count' ][ 'value' ]       = $this->records->get_records_count($this->user_id, array( 'categorie'=>$id, 'type_of_record' => 'value',       'datefrom' => $this->data[ 'current' ]['datefrom'], 'dateto' => $this->data[ 'current' ]['dateto'] ) );

            $this->data[ 'tabs' ] = tabs_buttons ( $username, $this->data['current'], $list[ 'count' ] );
            $this->data['activity']= $this->activities->get_activity_by_id_full( $id );
        }


        $this->data['records']= $this->_getRecords($username, $type_cat, $id, $this->data[ 'current' ]['tab'] , $this->data[ 'current' ]['datefrom'],  $this->data[ 'current' ]['dateto']);

        if ($type_cat=='categorie') {

            $this->data[ 'categorie' ] = $this->categories->get_categorie_by_id( $id );

            $this->data[ 'breadcrumb' ][]= array( 'title'=> 'categories', 'url'=>tt_url($username,'summary',$this->data['current'],array('cat'=>'categorie', 'id'=>NULL )) );

            if ( $this->data[ 'categorie' ]['id']!=NULL) {
                if ( $this->data[ 'categorie' ]['title']=='')
                    $this->data[ 'breadcrumb' ][]= array( 'title'=> '(no categorie)', 'url'=>tt_url($username,'summary',$this->data['current'],array('cat'=>'categorie', 'id'=>$this->data[ 'categorie' ]['id'] )) );
                else
                    $this->data[ 'breadcrumb' ][]= array( 'title'=> $this->data[ 'categorie' ]['title'], 'url'=>tt_url($username,'summary',$this->data['current'],array('cat'=>'categorie', 'id'=>$this->data[ 'categorie' ]['id'] )) );
            }

            $this->data[ 'title' ]='summary for categorie: '.$this->data[ 'categorie' ]['title'];
            if ( $this->data[ 'categorie' ]['title']=='') $this->data[ 'title' ].='(no categorie)';

        }

        elseif ($type_cat=='tag'){

            $this->data[ 'tag' ] = $this->tags->get_tag_by_id( $id );

            $this->data[ 'breadcrumb' ][]= array( 'title'=> $this->data[ 'tag' ]['tag'], 'url'=>tt_url($username,'summary',$this->data['current'],array('cat'=>'tag','id'=>$this->data[ 'tag' ]['id'])) );

            $this->data[ 'title' ]='summary for tag : '.$this->data[ 'tag' ]['tag'];

        }

        elseif ($type_cat=='value_type'){

            $this->data[ 'value_type' ] = $this->values->get_valuetype_by_id( $id );

            $this->data[ 'breadcrumb' ][]= array( 'title'=> $this->data[ 'value_type' ]['title'], 'url'=>tt_url($username,'summary',$this->data['current'],array('cat'=>'value_type','id'=>$this->data[ 'value_type' ]['id'])) );

            $this->data[ 'title' ]='summary for value type : '.$this->data[ 'value_type' ]['title'];

        }

        else {

            $this->data[ 'activity' ] = $this->activities->get_activity_by_id_full( $id );

            $this->data[ 'breadcrumb' ][]= array( 'title'=> 'categories', 'url'=>tt_url($username,'summary',$this->data['current'],array('cat'=>'categorie', 'id'=>NULL )) );
            if ( $this->data[ 'activity' ][ 'categorie' ]['title']!='')
                $this->data[ 'breadcrumb' ][]= array( 'title'=> $this->data[ 'activity' ][ 'categorie' ]['title'], 'url'=>tt_url($username,'summary',$this->data['current'],array('cat'=>'categorie','id'=>$this->data[ 'activity' ][ 'categorie' ]['id'])) );

            $this->data[ 'breadcrumb' ][]= array( 'title'=> $this->data[ 'activity' ]['title'], 'url'=>tt_url($username,'summary',$this->data['current'],array('cat'=>$this->data[ 'activity' ]['type_of_record'],'id'=>$this->data[ 'activity' ]['id'])) );

            $this->data[ 'title' ]='summary for '.$this->data[ 'activity' ]['type_of_record'].': '.$this->data[ 'activity' ]['title'];

        }



        if ($this->data['records']) {
            usort( $this->data['records'] , array("Timetracker_viz", "_orderByCat"));
            $this->data['stats']= $this->_getStats($this->data['records'], $type_cat, $this->data[ 'current' ]['datefrom'], $this->data[ 'current' ]['dateto']);
        }


        $this->data[ 'tt_layout' ]          = 'tt_summary';
        
        $this->data[ 'tabs' ]  =  array(
            array( 'title'=>'records', 'url'=>site_url('tt/'.$username.'/activities') ) ,
            array( 'title'=>'summary', 'url'=>site_url('tt/'.$username.'/activities/summary'), 'active'=>TRUE ) ,
            array( 'title'=>'graph', 'url'=>site_url('tt/'.$username.'/activities/graph')  )
           );


        $this->timetracker_lib->render();
    }



     public function graph( $username = NULL ) {

         // TODO gestion date bugue

        $this->timetracker_lib->checkUsername( $username );


        $count = $this->config->item('headerbloc_perpage');
        $this->data[ 'last_activities' ] =  $this->records->get_records_full($this->user_id, array( 'type_of_record' => 'activity', 'running' => 0 ) ,0 , $count );



        $this->data['datagraph']= $this->data['current'];
        unset($this->data['datagraph']["page"]);
        unset($this->data['datagraph']["tab"]);
        $this->data['datagraph']['username']=$username;
        
        $this->data[ 'tabs' ]  =  array(
            array( 'title'=>'records', 'url'=>site_url('tt/'.$username.'/activities') ) ,
            array( 'title'=>'summary', 'url'=>site_url('tt/'.$username.'/activities/summary') ) ,
            array( 'title'=>'graph', 'url'=>site_url('tt/'.$username.'/activities/graph'), 'active'=>TRUE  )
           );


        $this->data[ 'tt_layout' ]          = 'tt_graph';
        $this->timetracker_lib->render();
    }



    public function export( $username = NULL, $type_cat = 'categories', $id = NULL, $date_from = NULL, $date_to=NULL, $format = 'json' ) {
        // TODO gestion date bugue

        $this->load->helper('download');
        $this->timetracker_lib->checkUsername( $username );


        $records= $this->_getRecords($username, $type_cat, $id, NULL, $datefrom, $dateto);

        $this->output->enable_profiler( FALSE );

        // TODO modif entetes


        if ($format == 'csv') { // use content_output
            $content = '"id","start_time","duration","stop_at","trim_duration","running","title","activity_ID","categorie_ID","type_of_record","tags","value","description"'."\r\n";
            foreach ( $records as $k => $record )
                $content .= str_replace( array("\r","\n"), " ", $record['id'].',"'.$record['start_time'].'",'.$record['duration'].',"'.$record['stop_at'].'",'.@$record['trim_duration'].','.$record['running'].',"'.$record['activity']['activity_path'].'",'.$record['activity_ID'].','.$record['categorie_ID'].',"'.$record['activity']['type_of_record'].'","'.@$record['tags_path'].'","'.@$record['value']['value_path'].'","'.$record['description'].'"')."\r\n";

            $this->output
                ->set_content_type('text/csv');
            force_download("tt_".$username."_ci.csv", $content);

        }

        if ($format == 'json') {
            //$content= json_encode($records,JSON_NUMERIC_CHECK);
            $content= json_encode($records);
            $this->output
            ->set_content_type('application/json')
            ->set_output( $content );
        }


         if ($format == 'txt') { // use content_output

            $content= draw_text_table($records);


            //STATS

            if ($records) {
                usort($records , array("Timetracker_viz", "_orderByCat"));
                $stats= $this->_getStats($records, $type_cat,$this->data['dates']['min'],$this->data['dates']['max']);
            }

            if (isset($stats['categorie']))  $content .=  "\r\n\r\ncategories\r\n".draw_text_table($stats['categorie']);

            if (isset($stats['activity']))  $content .=  "\r\n\r\nactivities\r\n".draw_text_table($stats['activity']);
            if (isset($stats['activity_tag']))  $content .=  "\r\n\r\nactivities tags\r\n".draw_text_table($stats['activity_tag']);

            if (isset($stats['todo']))  $content .=  "\r\n\r\ntodos\r\n".draw_text_table($stats['todo']);
            if (isset($stats['todo_tag']))  $content .=  "\r\n\r\ntodos tags\r\n".draw_text_table($stats['todo_tag']);

            if (isset($stats['value']))  $content .=  "\r\n\r\nvalues\r\n".draw_text_table($stats['value']);
            if (isset($stats['value_tag']))  $content .=  "\r\n\r\nvalues tags\r\n".draw_text_table($stats['value_tag']);


            $this->output
                ->set_content_type('text/txt')
                ->set_output( $content );
            force_download("tt_".$username."_ci.txt", $content);

             //TODO add date and select params to json
        }


    }



// JSON Graphs

 public function json_activities_graph( $username,  $id, $datefrom, $dateto, $group_by ) {

        $type_cat='categorie';
        $this->load->helper('download');
        $this->timetracker_lib->checkUsername( $username );

        $records= $this->_getRecords(   $username, $type_cat, $id, 'activity', $datefrom,$dateto);
        $param=$this->_getRecordsParam( $username, $type_cat, $id, 'activity' , $datefrom,$dateto );

        $this->output->enable_profiler( FALSE );

        switch ($group_by) {
            case 'minute':
                $timelapse=array( 60 ,$group_by);
                break;
            case 'hour':
                $timelapse=array( 60*60 ,$group_by);
                break;
            case 'day':
                $timelapse=array( 60*60*24 ,$group_by);
                break;
            case 'week':
                $timelapse=array( 60*60*24*7 ,$group_by);
                break;
        }

        $data['min'] = $datefrom;
        $data['max'] = date( 'Y-m-d', strtotime($dateto)+(24*60*60) );

        for ($t=strtotime($datefrom); $t<strtotime($data['max']); $t+=$timelapse[0]) {
            $rec=array( 'time'=>date( 'Y-m-d H:i:s', $t), 'total'=>0, 'activities'=>array() );
            $activities=array();

            // add activities
            if ($records!=NULL)
            foreach ( $records as $k => $record ) {
               $record['trim_duration']= $this->records->trim_duration($record, $t, $t+$timelapse[0] );
              if ($record['trim_duration']>0) {
                  if (!isset($activities[$record['activity']['id']])) $activities[$record['activity']['id']]= array('duration'=>0, 'activity'=>$record['activity']['activity_path'], 'activity_ID'=>$record['activity']['id'] );
                 $activities[$record['activity']['id']]['duration']+= $record['trim_duration'];
                 $rec['total']+=$record['trim_duration'];
              }
            }

            //sort($activities);
            foreach ( $activities as $k => $activity ) $rec['activities'][]=$activity;


            $data['times'][]=$rec;
        }



         //$content= json_encode($data,JSON_NUMERIC_CHECK);
         $content= json_encode($data);
            $this->output
            ->set_content_type('application/json')
            ->set_output( $content );
    }



// TOOLS




    private function _getRecords( $username, $type_cat, $id, $type_of_record , $datefrom, $dateto ) {

        $param=$this->_getRecordsParam( $username, $type_cat, $id, $type_of_record , $datefrom, $dateto );

        $res= $this->records->get_records_full($this->user_id, $param);

        return $res;
    }



    private function _getRecordsParam( $username, $type_cat, $id, $type_of_record , $datefrom, $dateto ) {
        $param=array('order'=>'ASC');

        if ($id=='all') $id=NULL;

         $param['type_of_record']=$type_of_record;


        if ($type_cat=='categorie') $param['categorie']=$id;

        if ($type_cat=='activity')  { $param['activity']=$id; $param['type_of_record']='activity'; }
        if ($type_cat=='todo')      { $param['activity']=$id; $param['type_of_record']='todo'; }
        if ($type_cat=='value')     { $param['activity']=$id; $param['type_of_record']='value'; }

        if ($type_cat=='tag')       $param['tags']= array( $id );
        if ($type_cat=='valuetype') $param['valuetype']=  $id;

        $param['datefrom'] = $datefrom;
        $param['dateto'] = $dateto;
        $param['order']='ASC';

        return $param;
    }



    private function _orderByCat( $a,$b ) {
        return ($a['activity']['categorie']['title'] < $b['activity']['categorie']['title']) ? -1 : 1;
    }






    private function _getStats($records, $type_cat, $datemin=NULL, $datemax=NULL) {
        $res = array( );

        //TODO couper les duree en fonction datemin max et pour les runnings
        foreach ($records as $k => $record ) {

            if (isset($record['trimmed_duration'])) $duration=$record['trimmed_duration'];
                else $duration=$record['duration'];



            if (!isset(  $res[ $record['activity']['type_of_record'].'_total'] ))  $res[ $record['activity']['type_of_record'].'_total']=0;
            if (!isset(  $res[ $record['activity']['type_of_record'].'_count'] ))  $res[ $record['activity']['type_of_record'].'_count']=0;

            $res[ $record['activity']['type_of_record'].'_total'] += $duration;
            $res[ $record['activity']['type_of_record'].'_count'] ++;

            // stat categorie
             if (!isset( $res[ 'categorie' ][ $record['activity']['type_of_record'] ][ $record['activity']['categorie']['id'] ] )) {
                    $res[ 'categorie' ][ $record['activity']['type_of_record'] ][ $record['activity']['categorie']['id'] ]= $record['activity']['categorie'];
                    $res[ 'categorie' ][ $record['activity']['type_of_record'] ][ $record['activity']['categorie']['id'] ]['count'] = 0;
                    $res[ 'categorie' ][ $record['activity']['type_of_record'] ][ $record['activity']['categorie']['id'] ]['total'] = 0;
             }

             $res[ 'categorie' ][ $record['activity']['type_of_record'] ][ $record['activity']['categorie']['id'] ]['count'] ++;
             $res[ 'categorie' ][ $record['activity']['type_of_record'] ][ $record['activity']['categorie']['id'] ]['total'] += $duration;

            // stat activity
             if (!isset( $res[ $record['activity']['type_of_record'] ][ $record['activity']['id'] ] )) {
                    $res[ $record['activity']['type_of_record'] ][ $record['activity']['id'] ]= $record['activity'];
                    $res[ $record['activity']['type_of_record'] ][ $record['activity']['id'] ]['count'] = 0;
                    $res[ $record['activity']['type_of_record'] ][ $record['activity']['id'] ]['total'] = 0;
             }

             $res[ $record['activity']['type_of_record'] ][ $record['activity']['id'] ]['count'] ++;
             $res[ $record['activity']['type_of_record'] ][ $record['activity']['id'] ]['total'] += $duration;


            // stat activity
            if (isset($record['tags']))
            foreach ($record['tags'] as $kt => $tag) {

                if (!isset(  $res[ $record['activity']['type_of_record'].'_tag_total'] ))  $res[ $record['activity']['type_of_record'].'_tag_total']=0;
                if (!isset(  $res[ $record['activity']['type_of_record'].'_tag_count'] ))  $res[ $record['activity']['type_of_record'].'_tag_count']=0;

                $res[ $record['activity']['type_of_record'].'_tag_total'] += $duration;
                $res[ $record['activity']['type_of_record'].'_tag_count'] ++;

                if (!isset( $res[ $record['activity']['type_of_record'].'_tag' ][ $tag['id'] ] )) {
                    $res[ $record['activity']['type_of_record'].'_tag' ][ $tag['id'] ]['tag']= $tag['tag'];
                    $res[ $record['activity']['type_of_record'].'_tag' ][ $tag['id'] ]['count'] = 0;
                    $res[ $record['activity']['type_of_record'].'_tag' ][ $tag['id'] ]['total'] = 0;
                    }

                $res[ $record['activity']['type_of_record'].'_tag' ][ $tag['id'] ]['count'] ++;
                $res[ $record['activity']['type_of_record'].'_tag' ][ $tag['id'] ]['total'] += $duration;

            }




        } // end foreach


        return $res;
    }



}

/* End of file test.php */
/* Location: ./application/controllers/timetracker_viz.php */
