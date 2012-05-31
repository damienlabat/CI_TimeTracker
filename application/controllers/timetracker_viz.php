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


    public function summary( $username = NULL, $type_cat = 'categories', $id = NULL, $date_plage = NULL ) {

        $this->_checkUsername( $username );

        $records= $this->_getRecords($username, $type_cat, $id, $date_plage);

        print_r($records);

       // echo json_encode($records,JSON_NUMERIC_CHECK);


       /* $this->data[ 'tt_layout' ]          = 'tt_board';

        $this->_render();*/
    }



    public function export( $username = NULL, $type_cat = 'categories', $id = NULL, $date_plage = NULL, $format = 'json' ) {

        $this->_checkUsername( $username );

        $records= $this->_getRecords($username, $type_cat, $id, $date_plage);

        $this->output->enable_profiler( FALSE );

        // TODO modif entetes


       if ($format == 'csv') echo 'csv';

       if ($format == 'json') echo json_encode($records,JSON_NUMERIC_CHECK);

       if ($format == 'txt') echo 'txt';
    }



    public function _getDatePlage($date_plage) {

        $res=NULL;

        $sp= preg_split("/_/",$date_plage);

        if (count($sp)!=2) return NULL;

        if ( $sp[1] == 'Y' ) {
            $d1 =  new DateTime( $sp[0].'-01-01');
            $d2 =  new DateTime( $sp[0].'-01-01');
            $d2->add( new DateInterval( 'P1Y' ) );
            }

       elseif ( $sp[1] == 'M' )  {
            $d1 =  new DateTime( $sp[0].'-01');
            $d2 =  new DateTime( $sp[0].'-01');
            $d2->add( new DateInterval( 'P1M' ) );
            }

        elseif ( $sp[1] == 'W' )  {
            $d1 =  new DateTime( $sp[0]);
            $d2 =  new DateTime( $sp[0]);
            $d2->add( new DateInterval( 'P1W' ) );
            }

        elseif ( $sp[1] == 'D' )  {
            $d1 =  new DateTime( $sp[0]);
            $d2 =  new DateTime( $sp[0]);
            $d2->add( new DateInterval( 'P1D' ) );
            }

        else {
            $d1 =  new DateTime( $sp[0]);
            $d2 =  new DateTime( $sp[1]);
            }

        return array( $d1->format('Y-m-d H:i:s'), $d2->format('Y-m-d H:i:s') );
    }



    public function _getRecords( $username, $type_cat, $id, $date_plage ) {
        $param=array('order'=>'ASC');


        if ($type_cat=='categorie') $param['categorie_id']=$id;
        if ($type_cat=='activity')  $param['activity_id']=$id;
        if ($type_cat=='tag')       $param['tags']= array( $id );
        if ($type_cat=='valuetype') $param['valuetype']=  $id;



        if ($date_plage) {
            $date_array=$this->_getDatePlage($date_plage);
            $param['datemin'] = $date_array[0];
            $param['datemax'] = $date_array[1];
            }

        $param['order']='ASC';

        return $this->records->get_records_full($this->user_id, $param);
    }



}

/* End of file test.php */
/* Location: ./application/controllers/timetracker_viz.php */
