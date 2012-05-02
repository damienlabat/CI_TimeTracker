<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Timetracker extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->output->enable_profiler(TRUE);

        $this->load->helper('url');
        $this->load->helper('assets_helper');

        $this->load->library('tank_auth');
        $this->load->library('timetracker_lib');

        if ( !$this->tank_auth->is_logged_in() )   redirect('auth/', 'location', 301);
    }


    public function _render(){
        $this->load->view('layout',$this->data);
    }

    public function index()
    {
        //$this->data['headtitle']='Damien Labat';

        $this->data['content'] = $this->load->view('timetracker/form/classicform',NULL,true);
        $this->_render();
    }



}

/* End of file test.php */
/* Location: ./application/controllers/timetracker.php */
