<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->output->enable_profiler(TRUE);
        $this->load->helper('url');

        $this->load->library('tank_auth');

        if ( !$this->tank_auth->is_logged_in() )   redirect('auth/', 'location', 301);
    }


    public function index()
    {
        $this->load->library('tank_auth');

        /* $this->timetracker->update_categorie('t1/t2',array("title"=>"toto") );
        var_dump( $this->timetracker->get_categorie_from_path('t1/toto') );*/



      //  print_r( $this->timetracker->create_activity('test activity','t1/t2',array('tags'=>array('taaaggggg'))) );

      print_r( $this->timetracker->get_value_type_list_list() );
    }


    public function _unauthorized()
    {

    }
}

/* End of file test.php */
/* Location: ./application/controllers/test.php */
