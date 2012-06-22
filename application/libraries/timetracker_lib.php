<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Timetracker_lib
{


    function __construct()
    {
        $this->ci =& get_instance();

         $this->ci->load->helper( array(
            'url',
            'assets_helper',
            'form',
            'timetracker',
            'date',
            'array'
        ) );

        $this->ci->load->library( 'tank_auth' );

        $this->ci->load->model( array(
            'timetracker/categories',
            'timetracker/activities',
            'timetracker/tags',
            'timetracker/values',
            'timetracker/records'
        ) );

        //$this->ci->output->enable_profiler( TRUE );


    }


    function render( ) {

        $this->ci->data['cattree'] = $this->getCattree();

        $this->ci->data[ 'content' ] = $this->ci->load->view( 'timetracker/layout', $this->ci->data, true );
        $this->ci->load->view( 'layout', $this->ci->data );

        if ( 0 ) { //DEBUG
            echo '<pre>';
             print_r($this->ci->data);
             echo'</pre>';
         }
    }



    function getCattree() {

        $res = $this->ci->categories->get_categories( $this->ci->user_id );
        $current = $this->ci->data['current'];
        $cat_id = $current_cat = $current_activity =NULL;


        if ( ( $current['cat'] == 'categorie' ) && ( $current['id'] != NULL ) )  $current_cat = $cat_id = $current['id'];

        if ( in_array( $current['cat'], array('activity','todo','value') ) ) {
            $cat_id = $this->ci->data['activity']['categorie_ID'];
            $current_activity = $this->ci->data['activity']['id'];
        }

        if ( $current['cat'] == 'record' ) {
            $cat_id = $this->ci->data['record']['activity']['categorie_ID'];
            $current_activity = $this->ci->data['record']['activity']['id'];
        }

        if ( $cat_id != NULL ) {
            $activities =  $this->ci->activities->get_categorie_activities($cat_id);

            if ($current_activity != NULL )
                foreach( $activities as $k=>$activity)
                    if ( $activity['id'] ==  $current_activity )
                        $activities[$k]['active'] = TRUE;

            foreach( $res as $k=>$cat) {
                if ( $cat['id'] ==  $cat_id )
                    $res[$k]['activities'] = $activities;
                if ( $cat['id'] == $current_cat) $res[$k]['active'] = TRUE;
            }
        }

        return $res;
    }





    function checkuser()
    {

        if ( !$this->ci->tank_auth->is_logged_in() ) $this->goLogin();

        $this->ci->user_id   = $this->ci->tank_auth->get_user_id();
        $this->ci->user_name = $this->ci->tank_auth->get_username();
        $this->ci->user_profile = $this->ci->tank_auth->get_profile();

        $this->ci->user_params = json_decode( $this->ci->user_profile['params'], true );

        $this->ci->data[ 'user' ][ 'name' ] =        $this->ci->user_name;
        $this->ci->data[ 'user' ][ 'id' ]   =        $this->ci->user_id;
        $this->ci->data[ 'user' ][ 'params' ]   =    $this->ci->user_params;

        // SET MySQL timezone to user timezone;
        $this->ci->db->query( "SET time_zone= '".$this->ci->user_profile['timezone']."'" );


        $query = $this->ci->db->query( "SELECT NOW() as now" );
        $this->ci->server_time   =$query->row()->now;
        $this->ci->data[ 'server_time' ]=$this->ci->server_time;

        $this->ci->server_date   = preg_split('/ /', $this->ci->server_time);
        $this->ci->data[ 'server_date' ]=  $this->ci->server_date= $this->ci->server_date[0];

        $this->ci->firstdata_date   = $this->ci->records->get_min_time($this->ci->user_id);
        $this->ci->data[ 'firstdata_date' ]=  $this->ci->firstdata_date;

         /** get data **/

         $this->getRunnings();

        $tab = $this->ci->input->get( 'tab', TRUE );
        if ( $tab===FALSE ) $tab='activity';
        $this->ci->data[ 'current' ]['tab'] = $tab;

        $datefrom = $this->ci->input->get( 'datefrom', TRUE );
        $dateto =   $this->ci->input->get( 'dateto', TRUE );
        if (!$datefrom) $datefrom = $this->ci->firstdata_date;
        if (!$dateto)   $dateto = $this->ci->server_date;
        $this->ci->data[ 'current' ]['datefrom'] = $datefrom;
        $this->ci->data[ 'current' ]['dateto'] = $dateto;


    }


    function getCurrentObj(){
        $current=$this->ci->data['current'];

        if ( $current['id'] !=NULL ) {

            if ( in_array( $current['cat'], array('activity','todo','value') ) )
                $this->ci->data[ 'activity' ]       = $res = $this->ci->activities->get_activity_by_id_full( $current['id'] );

            if ($current['cat']=='categorie')
                $this->ci->data[ 'categorie' ]      = $res = $this->ci->categories->get_categorie_by_id( $current['id'] );

            if ($current['cat']=='record')
                $this->ci->data[ 'record' ]         = $res = $this->ci->records->get_record_by_id_full( $current['id'] );

            if ($current['cat']=='tag')
                 $this->ci->data[ 'tag' ]           = $res = $this->ci->tags->get_tag_by_id($current['id'] );

            if ($current['cat']=='valuetype')
                $this->ci->data[ 'valuetype' ]      = $res = $this->ci->values->get_valuetype_by_id( $valuetype_id );

            if ( !$res )  show_404();

        }
    }


    function getRunnings(){
        $this->ci->data[ 'running' ][ 'activities' ]    = $this->ci->records->get_records_full($this->ci->user_id, array( 'type_of_record' => 'activity',  'running' => TRUE ) );
        $this->ci->data[ 'running' ][ 'todos' ]         = $this->ci->records->get_records_full($this->ci->user_id, array( 'type_of_record' => 'todo',      'running' => TRUE ) );

    }



    function goLogin( ) {
        redirect( 'login', 'location', 301 );
    }


    function checkUsername( $username ) {
        if ( $username != $this->ci->user_name )
            $this->goLogin(); //TODO shared folder gestion
    }


    function get_alerts(){
        $this->ci->data[ 'alerts' ] = array( );
        if ( $this->ci->session->flashdata( 'alerts' ) )
            $this->ci->data[ 'alerts' ] = $this->ci->session->flashdata( 'alerts' ); //array( array('type'=>'success', 'alert'=>'error 1 .....') );

    }

}

/* End of file Tank_auth.php */
/* Location: ./application/libraries/Tank_auth.php */