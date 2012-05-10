<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Timetracker extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->output->enable_profiler(TRUE);

        $this->load->helper( array('url','assets_helper','form','timetracker'));
        $this->load->library( array('tank_auth','timetracker_lib') );

        $this->data['alerts']=array();

        if ($this->session->flashdata('alerts')) $this->data['alerts']=$this->session->flashdata('alerts');//array( array('type'=>'success', 'alert'=>'error 1 .....') );


        if ( !$this->tank_auth->is_logged_in() ) {
             $this->_goLogin();
          }
        else {
            $this->data['user_name']=$this->tank_auth->get_username();
            $this->data['user_id']=$this->tank_auth->get_user_id();

        }

         if ($_POST) {
             $res=$this->timetracker_lib->fromPOST($_POST);
             if (isset($res['alerts'])) $this->data['alerts']= array_merge( $this->data['alerts'], $res['alerts'] );
            }

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

        //if ($type_of_record!='tracking') $this->show404();  //TODO controlle du type
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

        $this->data['show_form']=TRUE;
        $this->data['running_activities']= $this->timetracker_lib->get_running_activities();
        $this->data['todos']= $this->timetracker_lib->get_running_TODO();
        $this->data['last_actions']= $this->timetracker_lib->get_last_actions();

        $this->_render();
    }



    /*****
     *  stop record
     *  */
    public function stop($username,$record_id)
    {
        $this->_checkUsername($username);

        $stopped= $this->timetracker_lib->stop_record($record_id);

        if (isset($stopped['alerts'])) $this->session->set_flashdata('alerts', $stopped['alerts'] );
        redirect('tt/'.$username, 'location');
    }



    /*****
     *  edit record
     *  */
    public function edit_record($username,$record_id)
    {
        $this->_checkUsername($username);
        // TODO!
        $this->data['TODO']="edit record ".$record_id;
        $this->_render();
    }


    /*****
     *  delete record
     *  */
    public function delete_record($username,$record_id)
    {
        $this->_checkUsername($username);
        // TODO! get confirmed from get val: ?confirmed
        $this->data['TODO']="delete record ".$record_id." confirmation page ??";
        $this->_render();
    }



     /*****
     *  restart record
     *  */
    public function restart($username,$record_id)
    {
        $this->_checkUsername($username);
        // TODO!
        $this->data['TODO']="restart record ".$record_id;
        $this->_render();
    }



    /*****
     *  list activities
     *  */

    public function activities($username)
    {
        $this->_checkUsername($username);
        // TODO!
        $this->data['TODO']="activities page";
        $this->_render();
    }


     /*****
     *  show activity
     *  */

    public function activity($username,$activity_id=NULL)
    {
        $this->_checkUsername($username);
        if ($activity_id==NULL) redirect('tt/'.$username.'/activities', 'location', 301);
        $this->_checkRecordType($activity_id,'activity');
        // TODO!
        $this->data['TODO']="activity ".$activity_id." page";
        $this->_render();
    }


     /*****
     *  show edit activity
     *  */

    public function activity_edit($username,$activity_id)
    {
        $this->_checkUsername($username);
        $this->_checkRecordType($activity_id,'activity');
        // TODO!
         $this->data['TODO']="activity ".$activity_id." edit";
        $this->_render();
    }



     /*****
     *  list todo things
     *  */

    public function thingstodo($username)
    {
        $this->_checkUsername($username);
        // TODO!
        $this->data['TODO']="things todo page";
        $this->_render();
    }


     /*****
     *  show todo
     *  */

    public function todo($username,$activity_id=NULL)
    {
        $this->_checkUsername($username);
        if ($activity_id==NULL) redirect('tt/'.$username.'/thingstodo', 'location', 301);
        $this->_checkRecordType($activity_id,'todo');
        // TODO!
        $this->data['TODO']="todo ".$activity_id." page";
        $this->_render();
    }


     /*****
     *  show edit todo
     *  */

    public function todo_edit($username,$activity_id)
    {
        $this->_checkUsername($username);
        $this->_checkRecordType($activity_id,'todo');
        // TODO!
        $this->data['TODO']="todo ".$activity_id." edit";
        $this->_render();
    }




     /*****
     *  list values
     *  */

    public function values($username)
    {
        $this->_checkUsername($username);
        // TODO!
        $this->data['TODO']="values page";
        $this->_render();
    }


     /*****
     *  show value
     *  */

    public function value($username,$activity_id=NULL)
    {
        $this->_checkUsername($username);
        if ($activity_id==NULL) redirect('tt/'.$username.'/values', 'location', 301);
        $this->_checkRecordType($activity_id,'value');
        // TODO!
        $this->data['TODO']="value ".$activity_id." page";
        $this->_render();
    }


     /*****
     *  show edit value
     *  */

    public function value_edit($username,$activity_id)
    {
        $this->_checkUsername($username);
        $this->_checkRecordType($activity_id,'value');
        // TODO!
        $this->data['TODO']="value ".$activity_id." edit";
        $this->_render();
    }





     /*****
     *  list categories
     *  */

    public function categories($username)
    {
        $this->_checkUsername($username);
        // TODO!
        $this->data['TODO']="categories";
        $this->_render();
    }


     /*****
     *  show categorie
     *  */

    public function categorie($username,$categorie_id=NULL)
    {
        $this->_checkUsername($username);
        // TODO!
        if ($categorie_id==NULL) redirect('tt/'.$username.'/categories', 'location', 301);
        $this->data['TODO']="categorie ".$categorie_id." page";
        $this->_render();
    }



     /*****
     *  show categorie edit
     *  */

    public function categorie_edit($username,$categorie_id)
    {
        $this->_checkUsername($username);
        // TODO!
        $this->data['TODO']="categorie ".$categorie_id." edit";
        $this->_render();
    }





     /*****
     *  list tags
     *  */

    public function tags($username)
    {
        $this->_checkUsername($username);
        // TODO!
         $this->data['TODO']="tags page";
        $this->_render();
    }


     /*****
     *  show tag
     *  */

    public function tag($username,$tag_id=NULL)
    {
        $this->_checkUsername($username);
        if ($tag_id==NULL) redirect('tt/'.$username.'/tags', 'location', 301);
        // TODO!
        $this->data['TODO']="tag ".$tag_id." page";
        $this->_render();
    }



     /*****
     *  show tag edit
     *  */

    public function tag_edit($username,$tag_id)
    {
        $this->_checkUsername($username);
        // TODO!
        $this->data['TODO']="tag ".$tag_id." edit";
        $this->_render();
    }





     /*****
     *  list value_types
     *  */

    public function valuetypes($username)
    {
        $this->_checkUsername($username);
        // TODO!
        $this->data['TODO']="value types page";
        $this->_render();
    }


     /*****
     *  show value_types
     *  */

    public function valuetype($username,$valuetype_id=NULL)
    {
        $this->_checkUsername($username);
        if ($valuetype_id==NULL) redirect('tt/'.$username.'/valuetypes', 'location', 301);
        // TODO!
        $this->data['TODO']="valuetype ".$valuetype_id." page";
        $this->_render();
    }



     /*****
     *  show value_types edit
     *  */

    public function valuetype_edit($username,$valuetype_id)
    {
        $this->_checkUsername($username);
        // TODO!
        $this->data['TODO']="valuetype ".$valuetype_id." edit";
        $this->_render();
    }





    /*****
     *  show summary
     *  */

    public function summary($username,$type_obj=NULL, $id_obj=NULL)
    {
        $this->_checkUsername($username);
        // TODO!
        $this->data['TODO']="summary $type_obj $id_obj";
        $this->_render();
    }


     /*****
     *  show stats
     *  */

    public function stats($username,$type_obj=NULL, $id_obj=NULL)
    {
        $this->_checkUsername($username);
        // TODO!
        $this->data['TODO']="stats $type_obj $id_obj";
        $this->_render();
    }


    /*****
     *  show export
     *  */

    public function export($username,$type_obj=NULL, $id_obj=NULL)
    {
        $this->_checkUsername($username);
        // TODO!
        $this->data['TODO']="export $type_obj $id_obj";
        $this->_render();
    }



    /*****
     *  show params
     *  */

    public function params($username)
    {
        $this->_checkUsername($username);
        // TODO!
        $this->data['TODO']="params";
        $this->_render();
    }





}

/* End of file test.php */
/* Location: ./application/controllers/timetracker.php */
