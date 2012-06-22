<?php
if ( !defined( 'BASEPATH' ) )
    exit( 'No direct script access allowed' );

class Staticpages extends CI_Controller {
    function __construct( ) {
        parent::__construct();
        $this->load->helper( 'url' );
        $this->load->helper( 'assets_helper' );

        $this->load->library( array(
             'tank_auth'
        ) );

        //$this->output->enable_profiler(TRUE);

        $this->data = array( );

        if ( $this->tank_auth->is_logged_in() ) {

            $this->load->library( 'timetracker_lib' );
            $this->timetracker_lib->checkuser();
            $this->timetracker_lib->get_alerts();
        }
    }


    public function index( ) {
        $this->data[ 'content' ] = $this->load->view( 'welcome', $this->data, true );
        $this->load->view( 'layout', $this->data );
    }

    public function help( ) {
        $this->data[ 'content' ] = $this->load->view( 'help', $this->data, true );
        $this->load->view( 'layout', $this->data );
    }



}

/* End of file staticpages.php */
/* Location: ./application/controllers/staticpages.php */