<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Timetracker extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->output->enable_profiler(TRUE);

        $this->load->helper( array('url','assets_helper','form','timetracker'));
        $this->load->library( array('tank_auth','timetracker_lib') );


        if ( !$this->tank_auth->is_logged_in() ) {
             $this->_goLogin();
          }
        else {
            $this->data['user_name']=$this->tank_auth->get_username();
            $this->data['user_id']=$this->tank_auth->get_user_id();
        }
    }


    public function _render(){
        $this->data['content'] = $this->load->view('timetracker/layout',$this->data,true);
        $this->load->view('layout',$this->data);
    }

    public function _goLogin(){
        redirect('login', 'location', 301);
    }

    public function _checkUsername($username){
        if ($username!=$this->data['user_name']) $this->_goLogin(); //TODO shared folder gestion
    }

    public function index($username)
    {
        $this->_checkUsername($username);

        if ($_POST) $this->timetracker_lib->fromPOST($_POST);

        $this->data['running_activities']= $this->timetracker_lib->get_running_activities();
        $this->data['last_activities']= $this->timetracker_lib->get_last_activities();

        $this->_render();
    }


    public function add()
    {
        if ($_POST) $this->timetracker_lib->fromPOST($_POST);
        redirect(current_url(), 'location');
    }

    public function stop($activity_id)
    {
        $this->timetracker_lib->stop_activity($activity_id);
        redirect(current_url(), 'location');
    }



}

/* End of file test.php */
/* Location: ./application/controllers/timetracker.php */
