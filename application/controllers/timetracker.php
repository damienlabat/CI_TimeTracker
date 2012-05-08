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

         if ($_POST) $this->timetracker_lib->fromPOST($_POST);

    }




/* ==========================
 *  rendering & redirection
 * ========================== */

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

    public function _checkRecordType($record_id,$type_of_record){
        // recup type

        if ($type_of_record!='tracking') $this->show404();  //TODO controlle du type
    }



/* ==========================
 *  actions
 * ========================== */


    /******
     * tt board
     * */

    public function index($username=NULL)
    {
        $this->_checkUsername($username);

      //  $this->data['running_activities']= $this->timetracker_lib->get_running_activities();
       // $this->data['last_activities']= $this->timetracker_lib->get_last_activities();

        $this->_render();
    }



    /*****
     *  stop record
     *  */

    public function stop($username,$record_id)
    {
        $this->_checkUsername($username);

        $this->timetracker_lib->stop_record($record_id);
        redirect('tt/'.$username, 'location');
    }







    /*****
     *  list activities
     *  */

    public function activities($username)
    {
        $this->_checkUsername($username);
        // TODO!
    }


     /*****
     *  show activity
     *  */

    public function activity($username,$record_id)
    {
        $this->_checkUsername($username);
        $this->_checkRecordType($record_id,'tracking');
        // TODO!
    }


     /*****
     *  show edit activity
     *  */

    public function activity_edit($username,$record_id)
    {
        $this->_checkUsername($username);
        $this->_checkRecordType($record_id,'tracking');
        // TODO!
    }



     /*****
     *  list todo things
     *  */

    public function thingstodo($username)
    {
        $this->_checkUsername($username);
        // TODO!
    }


     /*****
     *  show todo
     *  */

    public function todo($username,$record_id)
    {
        $this->_checkUsername($username);
        $this->_checkRecordType($record_id,'todo');
        // TODO!
    }


     /*****
     *  show edit todo
     *  */

    public function todo_edit($username,$record_id)
    {
        $this->_checkUsername($username);
        $this->_checkRecordType($record_id,'tracking');
        // TODO!
    }




     /*****
     *  list values
     *  */

    public function values($username)
    {
        $this->_checkUsername($username);
        // TODO!
    }


     /*****
     *  show value
     *  */

    public function value($username,$record_id)
    {
        $this->_checkUsername($username);
        $this->_checkRecordType($record_id,'value');
        // TODO!
    }


     /*****
     *  show edit value
     *  */

    public function value_edit($username,$record_id)
    {
        $this->_checkUsername($username);
        $this->_checkRecordType($record_id,'tracking');
        // TODO!
    }





     /*****
     *  list categories
     *  */

    public function categories($username)
    {
        $this->_checkUsername($username);
        // TODO!
    }


     /*****
     *  show categorie
     *  */

    public function categorie($username,$categorie_id)
    {
        $this->_checkUsername($username);
        // TODO!
    }



     /*****
     *  show categorie edit
     *  */

    public function categorie_edit($username,$categorie_id)
    {
        $this->_checkUsername($username);
        // TODO!
    }





     /*****
     *  list tags
     *  */

    public function tags($username)
    {
        $this->_checkUsername($username);
        // TODO!
    }


     /*****
     *  show tag
     *  */

    public function tag($username,$tag_id)
    {
        $this->_checkUsername($username);
        // TODO!
    }



     /*****
     *  show tag edit
     *  */

    public function tag_edit($username,$tag_id)
    {
        $this->_checkUsername($username);
        // TODO!
    }





     /*****
     *  list value_types
     *  */

    public function valuestypes($username)
    {
        $this->_checkUsername($username);
        // TODO!
    }


     /*****
     *  show value_types
     *  */

    public function valuetype($username,$valuetype_id)
    {
        $this->_checkUsername($username);
        // TODO!
    }



     /*****
     *  show value_types edit
     *  */

    public function valuetype_edit($username,$valuetype_id)
    {
        $this->_checkUsername($username);
        // TODO!
    }





    /*****
     *  show summary
     *  */

    public function summary($username,$type_obj=NULL, $id_obj=NULL)
    {
        $this->_checkUsername($username);
        // TODO!
    }


     /*****
     *  show stats
     *  */

    public function stats($username,$type_obj=NULL, $id_obj=NULL)
    {
        $this->_checkUsername($username);
        // TODO!
    }


    /*****
     *  show export
     *  */

    public function export($username,$type_obj=NULL, $id_obj=NULL)
    {
        $this->_checkUsername($username);
        // TODO!
    }



    /*****
     *  show params
     *  */

    public function params($username)
    {
        $this->_checkUsername($username);
        // TODO!
    }





}

/* End of file test.php */
/* Location: ./application/controllers/timetracker.php */
