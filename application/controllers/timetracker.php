<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Timetracker extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->output->enable_profiler(TRUE);

        $this->load->helper( array('url','assets_helper','form','timetracker'));
        $this->load->library( array('tank_auth','timetracker_lib') );


        if ( !$this->tank_auth->is_logged_in() )   redirect('auth/', 'location', 301);
    }


    public function _render(){
        $this->data['content'] = $this->load->view('timetracker/layout',$this->data,true);
        $this->load->view('layout',$this->data);
    }

    public function index()
    {

        if ($_POST) $this->timetracker_lib->fromPOST($_POST);

        $this->data['running_activities']= $this->timetracker_lib->get_running_activities();
        $this->data['last_activities']= $this->timetracker_lib->get_last_activities();

        $this->_render();
    }


    public function add()
    {
        if ($_POST) $this->timetracker_lib->fromPOST($_POST);
        redirect('timetracker', 'location');
    }

    public function stop($activity_id)
    {
        $this->timetracker_lib->stop_activity($activity_id);
        redirect('timetracker', 'location');
    }



}

/* End of file test.php */
/* Location: ./application/controllers/timetracker.php */
