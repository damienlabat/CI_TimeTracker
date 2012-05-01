<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->output->enable_profiler(TRUE);

    }


    public function index()
    {
        $this->load->library('tank_auth');

        /* $this->timetracker->update_categorie('t1/t2',array("title"=>"toto") );
        var_dump( $this->timetracker->get_categorie_from_path('t1/toto') );*/
        print_r( $this->timetracker->get_categories_path() );

    }
}

/* End of file test.php */
/* Location: ./application/controllers/test.php */
