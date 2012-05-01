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

        if ($this->tank_auth->is_logged_in())  echo "hello ".$this->tank_auth->get_username();
            else  echo "hello world";

        $this->timetracker->update_categorie('t2',NULL,array("description"=>"ma super description") );
        var_dump( $this->timetracker->get_categories_tree() );

    }
}

/* End of file test.php */
/* Location: ./application/controllers/test.php */
