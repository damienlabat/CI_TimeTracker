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



      //  print_r( $this->timetracker->create_activity('test activity','t1/t2',array('tags'=>array('taaaggggg'))) );
      print_r( $this->timetracker->remove_tag(15,'taaaggggg') );



    }
}

/* End of file test.php */
/* Location: ./application/controllers/test.php */
