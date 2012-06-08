<?php
if ( !defined( 'BASEPATH' ) )
    exit( 'No direct script access allowed' );

class Timetracker_viz extends CI_Controller {
    function __construct( ) {
        parent::__construct();
        $this->output->enable_profiler( TRUE );

        $this->load->helper( array(
             'url',
            'assets_helper',
            'form',
            'timetracker',
            'date',
            'array'
        ) );

        $this->load->library( 'tank_auth' );

        $this->load->model( array(
            'timetracker/categories',
            'timetracker/activities',
            'timetracker/tags',
            'timetracker/values',
            'timetracker/records'
        ) );

        $this->user_id   = $this->tank_auth->get_user_id();
        $this->user_name = $this->tank_auth->get_username();

        $this->data[ 'alerts' ] = array( );


        if ( $this->session->flashdata( 'alerts' ) )
            $this->data[ 'alerts' ] = $this->session->flashdata( 'alerts' ); //array( array('type'=>'success', 'alert'=>'error 1 .....') );


        if ( !$this->tank_auth->is_logged_in() ) {
            $this->_goLogin();
        }
        else {
            $this->data[ 'user_name' ] = $this->user_name;
            $this->data[ 'user_id' ]   = $this->user_id;
        }

    }




    /* ==========================
     *  rendering & redirection
     * ========================== */

    public function _render( ) {
        $this->data[ 'content' ] = $this->load->view( 'timetracker/layout', $this->data, true );
        $this->load->view( 'layout', $this->data );
    }

    public function _goLogin( ) {
        redirect( 'login', 'location', 301 );
    }

    public function _checkUsername( $username ) {
        if ( $username != $this->data[ 'user_name' ] )
            $this->_goLogin(); //TODO shared folder gestion
    }




    /* ==========================
     *  actions
     * ========================== */


    public function summary( $username = NULL, $type_cat = 'categorie', $id = NULL, $date_plage = 'all' ) {

        //TODO add title and breadcrumb

        $this->_checkUsername( $username );

        $tab = $this->input->get( 'tab', TRUE );
        if ( !in_array( $type_cat, array('activity','todo','value') ) ) {
            if ( $tab===FALSE ) $tab='activity';

            $this->data[ 'count' ][ 'activity' ]    = $this->records->get_records_count($this->user_id, $this->_getRecordsParam( $username, $type_cat, $id, 'activity' , $date_plage ) );
            $this->data[ 'count' ][ 'todo' ]        = $this->records->get_records_count($this->user_id, $this->_getRecordsParam( $username, $type_cat, $id, 'todo' , $date_plage ) );
            $this->data[ 'count' ][ 'value' ]       = $this->records->get_records_count($this->user_id, $this->_getRecordsParam( $username, $type_cat, $id, 'value' , $date_plage ) );
            $this->data[ 'tabs' ]               = tabs_buttons ( tt_url($username,'summary',$type_cat,$id, $date_plage), $this->data[ 'count' ], $tab );
        }


        $this->data['current']= array(
            "action" => 'summary',
            "type_cat" => $type_cat,
            "id" => $id,
            "date_plage" => $date_plage,
            "tab" => $tab
            );
        $this->data['records']= $this->_getRecords($username, $type_cat, $id, $tab , $date_plage);


        if ($type_cat=='categorie') {

            $this->data[ 'categorie' ] = $this->categories->get_categorie_by_id( $id );

            $this->data[ 'breadcrumb' ][]= array( 'title'=> 'categories', 'url'=>tt_url($username,'summary','categorie','all',$date_plage) );

            if ( $this->data[ 'categorie' ]['id']!=NULL) {
                if ( $this->data[ 'categorie' ]['title']=='')
                    $this->data[ 'breadcrumb' ][]= array( 'title'=> '_root_', 'url'=>tt_url($username,'summary','categorie',$id,  $date_plage) );
                else
                    $this->data[ 'breadcrumb' ][]= array( 'title'=> $this->data[ 'categorie' ]['title'], 'url'=>tt_url($username,'summary','categorie',$id,  $date_plage) );
            }

            $this->data[ 'title' ]='summary for categorie: '.$this->data[ 'categorie' ]['title'];
            if ( $this->data[ 'categorie' ]['title']=='') $this->data[ 'title' ].='_root_';

        }

        elseif ($type_cat=='tag'){

            $this->data[ 'tag' ] = $this->tags->get_tag_by_id( $id );

            $this->data[ 'breadcrumb' ][]= array( 'title'=> $this->data[ 'tag' ]['tag'], 'url'=>tt_url($username,'summary','tag',$this->data[ 'tag' ]['id'],  $date_plage) );

            $this->data[ 'title' ]='summary for tag : '.$this->data[ 'tag' ]['tag'];

        }

        elseif ($type_cat=='value_type'){

            $this->data[ 'value_type' ] = $this->values->get_valuetype_by_id( $id );

            $this->data[ 'breadcrumb' ][]= array( 'title'=> $this->data[ 'value_type' ]['title'], 'url'=>tt_url($username,'summary','value_type',$this->data[ 'value_type' ]['id'],  $date_plage) );

            $this->data[ 'title' ]='summary for value type : '.$this->data[ 'value_type' ]['title'];

        }

        else {

            $this->data[ 'activity' ] = $this->activities->get_activity_by_id_full( $id );

            if ( $this->data[ 'activity' ][ 'categorie' ]['title']!='')
                $this->data[ 'breadcrumb' ][]= array( 'title'=> $this->data[ 'activity' ][ 'categorie' ]['title'], 'url'=>tt_url($username,'summary','categorie',$this->data[ 'activity' ][ 'categorie' ]['id'],  $date_plage) );

            $this->data[ 'breadcrumb' ][]= array( 'title'=> $this->data[ 'activity' ]['title'], 'url'=>tt_url($username,'summary',$this->data[ 'activity' ]['type_of_record'],$this->data[ 'activity' ]['id'],  $date_plage) );

            $this->data[ 'title' ]='summary for '.$this->data[ 'activity' ]['type_of_record'].': '.$this->data[ 'activity' ]['title'];

        }



        if ($this->data['records']) {
            usort( $this->data['records'] , array("Timetracker_viz", "_orderByCat"));
            $this->data['stats']= $this->_getStats($this->data['records'], $type_cat,$this->data['dates']['min'],$this->data['dates']['max']);
        }


        $this->data[ 'tt_layout' ]          = 'tt_summary';


        $this->_render();
    }



     public function graph( $username = NULL, $type_cat = 'categories', $id = NULL, $date_plage = 'all', $type_graph = NULL ) {

        $this->_checkUsername( $username );

        $tab = $this->input->get( 'tab', TRUE );
        if ( !in_array( $type_cat, array('activity','todo','value') ) ) {
            if ( $tab===FALSE ) $tab='activity';

            // TODO
        }

        $this->data['current']= array(
            "action" => 'graph',
            "type_cat"=>$type_cat,
            "id"=>$id,
            "date_plage"=>$date_plage,
            "tab" => $tab,
            "type_graph"=>$type_graph
            );
        $this->data['records']= $this->_getRecords($username, $type_cat, $id, $tab, $date_plage);

        $this->data[ 'tt_layout' ]          = 'tt_graph';
        $this->_render();
    }



    public function export( $username = NULL, $type_cat = 'categories', $id = NULL, $date_plage = 'all', $format = 'json' ) {

        $this->load->helper('download');
        $this->_checkUsername( $username );

        $this->data['current']= array(
            "action" => 'export',
            "type_cat"=>$type_cat,
            "id"=>$id,
            "date_plage"=>$date_plage
            );

        $records= $this->_getRecords($username, $type_cat, $id, NULL, $date_plage);

        if ($records) {
            usort($records , array("Timetracker_viz", "_orderByCat"));
            $stats= $this->_getStats($records, $type_cat,$this->data['dates']['min'],$this->data['dates']['max']);
        }

        $this->output->enable_profiler( FALSE );

        // TODO modif entetes


        if ($format == 'csv') { // use content_output
            $content = '"id","start_time","diff_greenwich","duration","stop_at","trim_duration","running","title","activity_ID","categorie_ID","type_of_record","tags","value","description"'."\r\n";
            foreach ( $records as $k => $record )
                $content .= str_replace( array("\r","\n"), " ", $record['id'].',"'.$record['start_time'].'",'.$record['diff_greenwich'].','.$record['duration'].',"'.$record['stop_at'].'",'.@$record['trim_duration'].','.$record['running'].',"'.$record['activity']['activity_path'].'",'.$record['activity_ID'].','.$record['categorie_ID'].',"'.$record['activity']['type_of_record'].'","'.@$record['tags_path'].'","'.@$record['value']['value_path'].'","'.$record['description'].'"')."\r\n";

            $this->output
                ->set_content_type('text/csv');
            force_download("tt_".$username."_ci.csv", $content);

        }

        if ($format == 'json') {
            $content= json_encode($records,JSON_NUMERIC_CHECK);
            $this->output
            ->set_content_type('application/json')
            ->set_output( $content );
        }


         if ($format == 'txt') { // use content_output

            $content= draw_text_table($records);


            //STATS

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



    public function _getDatePlage($date_plage) {

        $d1=$d2=NULL;
        $type='all';

        $sp= preg_split("/_/",$date_plage);

        if (count($sp)!=2) return  array( 'min'=> NULL, 'max' => NULL, 'type'=> 'all', 'uri' => $date_plage);;

        if ( $sp[1] == 'Y' ) {
            $d1 =  new DateTime( $sp[0].'-01-01');
            $d2 =  new DateTime( $sp[0].'-01-01');
            $d2->add( new DateInterval( 'P1Y' ) );
            $type='year';
            }

       elseif ( $sp[1] == 'M' )  {
            $d1 =  new DateTime( $sp[0].'-01');
            $d2 =  new DateTime( $sp[0].'-01');
            $d2->add( new DateInterval( 'P1M' ) );
            $type='month';
            }

        elseif ( $sp[1] == 'W' )  {
            $d1 =  new DateTime( $sp[0]);
            $d2 =  new DateTime( $sp[0]);
            $d2->add( new DateInterval( 'P1W' ) );
            $type='week';
            }

        elseif ( $sp[1] == 'D' )  {
            $d1 =  new DateTime( $sp[0]);
            $d2 =  new DateTime( $sp[0]);
            $d2->add( new DateInterval( 'P1D' ) );
            $type='day';
            }

        else {
            $d1 =  new DateTime( $sp[0]);
            $d2 =  new DateTime( $sp[1]);
            $type='manual';
            }

        return array( 'min'=> $d1->format('Y-m-d H:i:s'), 'max' => $d2->format('Y-m-d H:i:s'), 'type'=> $type, 'uri' => $date_plage);
    }



    public function _getRecords( $username, $type_cat, $id, $type_of_record , $date_plage ) {

        $param=$this->_getRecordsParam( $username, $type_cat, $id, $type_of_record , $date_plage );

        $res= $this->records->get_records_full($this->user_id, $param);

        return $res;
    }



    public function _getRecordsParam( $username, $type_cat, $id, $type_of_record , $date_plage ) {
        $param=array('order'=>'ASC');

        if ($id=='all') $id=NULL;

         $param['type_of_record']=$type_of_record;


        if ($type_cat=='categorie') $param['categorie']=$id;

        if ($type_cat=='activity')  { $param['activity']=$id; $param['type_of_record']='activity'; }
        if ($type_cat=='todo')      { $param['activity']=$id; $param['type_of_record']='todo'; }
        if ($type_cat=='value')     { $param['activity']=$id; $param['type_of_record']='value'; }

        if ($type_cat=='tag')       $param['tags']= array( $id );
        if ($type_cat=='valuetype') $param['valuetype']=  $id;




            $date_array=$this->_getDatePlage($date_plage);
            $this->data['dates']= $date_array;
            $param['datemin'] = $date_array['min'];
            $param['datemax'] = $date_array['max'];




        $param['order']='ASC';

        return $param;
    }



    public function _orderByCat( $a,$b ) {
        return ($a['activity']['categorie']['title'] < $b['activity']['categorie']['title']) ? -1 : 1;
    }






    public function _getStats($records, $type_cat, $datemin=NULL, $datemax=NULL) {
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
