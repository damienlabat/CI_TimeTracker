<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('assets_helper');

        //$this->output->enable_profiler(TRUE);

        $this->data=array();
    }


    public function index()
    {
        $this->data['content'] = $this->load->view('welcome',NULL,true);
        $this->load->view('layout',$this->data);
    }



}

/* End of file test.php */
/* Location: ./application/controllers/welcome.php */
