<?php
if ( !defined( 'BASEPATH' ) )
    exit( 'No direct script access allowed' );

class Timetracker extends CI_Controller {
    function __construct( ) {
        parent::__construct();

       // if ( !$this->input->is_ajax_request() ) $this->output->enable_profiler(TRUE);

        $this->load->library( 'timetracker_lib' );
        $this->timetracker_lib->checkuser();
        $this->timetracker_lib->get_alerts();

        $this->data['current']['action']= 'record';



        if ( $_POST ) {
            $res = $this->_fromPOST( $_POST );
            if ( isset( $res[ 'alerts' ] ) )
                $this->data[ 'alerts' ] = array_merge( $this->data[ 'alerts' ], $res[ 'alerts' ] );
        }

    }







    /* ==========================
     *  actions
     * ========================== */


    /******
     * tt home
     * */

    public function home( $username = NULL ) {

        $this->timetracker_lib->checkUsername( $username );
        
        $this->data[ 'current' ]['cat'] = NULL;

        $this->data[ 'tt_layout' ] = 'tt_home';

        $count = $this->config->item('headerbloc_perpage');
        $this->data[ 'last_activities' ] =  $this->records->get_records_full($this->user_id, array( 'type_of_record' => 'activity', 'running' => 0 ) ,0 , $count );
        $this->data[ 'last_values' ] =      $this->records->get_records_full($this->user_id, array( 'type_of_record' => 'value' ) ,0 , $count );

        $this->timetracker_lib->render();
    }


    public function activities( $username = NULL ) {

        $this->timetracker_lib->checkUsername( $username );

        $page = isset($_GET['page']) ? $_GET['page']: 1;

        $this->data[ 'current' ]['page'] = 'activities';
        $this->data[ 'current' ]['tab'] = 'activity';
        $this->data[ 'current' ]['cat'] = 'categorie';
        $this->data[ 'current' ]['id']  = NULL;

        $count = $this->config->item('headerbloc_perpage');
        $this->data[ 'last_activities' ] =  $this->records->get_records_full($this->user_id, array( 'type_of_record' => 'activity', 'running' => 0 ) ,0 , $count );

        $this->load->library('pagination');

        $list_param=array(
            'type_of_record'=>'activity',
            'datefrom' => $this->data[ 'current' ]['datefrom'],
            'dateto' => $this->data[ 'current' ]['dateto']
             );

        $config['base_url'] = site_url('tt/'.$username.'/activities?');
        $config['total_rows'] = $this->records->get_records_count($this->user_id, $list_param  );

        $this->pagination->initialize($config);

        $per_page=$this->pagination->per_page;
        $offset= ( $page-1 ) * $per_page;


        $this->data['list'][ 'activity_records' ]   = $this->records->get_records_full($this->user_id, $list_param, $offset, $per_page );
        $this->data[ 'pager']                       = $this->pagination->create_links();


        $this->data[ 'tabs' ]  =  array(
            array( 'title'=>'records', 'url'=>'', 'active'=>TRUE ) ,
            array( 'title'=>'summary', 'url'=>site_url('tt/'.$username.'/activities/summary') ) ,
            array( 'title'=>'graph', 'url'=>site_url('tt/'.$username.'/activities/graph') )
            );

        $this->data[ 'tt_layout' ] = 'tt_activities';

        $this->timetracker_lib->render();
    }




    public function todolist( $username = NULL ) {

        $this->timetracker_lib->checkUsername( $username );

        $page = isset($_GET['page']) ? $_GET['page']: 1;

        $this->data[ 'current' ]['page'] = 'todolist';
        $this->data[ 'current' ]['tab'] = 'todo';
        $this->data[ 'current' ]['cat'] = 'categorie';
        $this->data[ 'current' ]['id']  = NULL;

        $this->load->library('pagination');

        $config['base_url'] = site_url('tt/'.$username.'/todolist?');
        $config['total_rows'] = $this->records->get_records_count($this->user_id, array( 'type_of_record'=>'todo' ) );

        $this->pagination->initialize($config);

        $per_page=$this->pagination->per_page;
        $offset= ( $page-1 ) * $per_page;


        $this->data['list'][ 'todo_records' ]        = $this->records->get_records_full($this->user_id, array(  'type_of_record'=>'todo' ), $offset, $per_page );
        $this->data[ 'pager']               = $this->pagination->create_links();

        $this->data[ 'tt_layout' ] = 'tt_todolist';

        $this->timetracker_lib->render();
    }


    public function values( $username = NULL ) {

        $this->timetracker_lib->checkUsername( $username );

        $page = isset($_GET['page']) ? $_GET['page']: 1;

        $this->data[ 'current' ]['page'] = 'values';
        $this->data[ 'current' ]['tab'] = 'value';
        $this->data[ 'current' ]['cat'] = 'categorie';
        $this->data[ 'current' ]['id']  = NULL;

        $count = $this->config->item('headerbloc_perpage');
        $this->data[ 'last_values' ] =  $this->records->get_records_full($this->user_id, array( 'type_of_record' => 'value', 'running' => 0 ) ,0 , $count );

        $this->load->library('pagination');

        $config['base_url'] = site_url('tt/'.$username.'/values?');
        $config['total_rows'] = $this->records->get_records_count($this->user_id, array( 'type_of_record'=>'value' ) );

        $this->pagination->initialize($config);

        $per_page=$this->pagination->per_page;
        $offset= ( $page-1 ) * $per_page;


        $this->data['list'][ 'value_records' ]        = $this->records->get_records_full($this->user_id, array(  'type_of_record'=>'value' ), $offset, $per_page );
        $this->data[ 'pager']               = $this->pagination->create_links();

        $this->data[ 'tt_layout' ] = 'tt_values';
      

        $this->timetracker_lib->render();
    }


    /*****
     *  stop record
     *  */
    public function stop( $username, $record_id ) {
        $this->timetracker_lib->checkUsername( $username );

        $record= $this->records->get_record_by_id_full( $record_id );
        if ( !$record )
            show_404();

        $stopped = $this->records->stop_record( $record_id );

        if ( isset( $stopped[ 'alerts' ] ) )
            $this->session->set_flashdata( 'alerts', $stopped[ 'alerts' ] );
  
        $this->timetracker_lib->redirect_type_of_record($record[ 'activity' ]['type_of_record']);
    }












    /*****
     *  restart record
     *  */
    public function restart( $username, $record_id ) {
        $this->timetracker_lib->checkUsername( $username );

        $this->data[ 'current' ]['cat'] = 'categorie';
        $this->data[ 'current' ]['id'] = NULL;
        $this->timetracker_lib->getCurrentObj();
        
        $record= $this->records->get_record_by_id_full( $record_id );
        if ( !$record )
            show_404();

        if ( $this->records->restart_record( $record_id ) )
            $alert = array(
                 array(
                     'type' => 'success',
                    'alert' => 'start new ' . $record['activity']['type_of_record'] . ' record !  "' . $record['activity']['activity_path'] . '"'
                )
            );
        else
            $alert = array(
                 array(
                     'type' => 'error',
                    'alert' => 'error !'
                )
            );

        $this->session->set_flashdata( 'alerts', $alert );
        
        $this->timetracker_lib->redirect_type_of_record($record[ 'activity' ]['type_of_record']);

    }



    /*****
     *  show activity
     *  */


    public function generic_activity_show( $username, $type_of_record, $activity_id =NULL, $page =1 ) {


        $this->timetracker_lib->checkUsername( $username );

        $this->data[ 'current' ]['cat'] = $type_of_record;
        $this->data[ 'current' ]['id'] = $activity_id;
        $this->data[ 'current' ]['username'] = $username;

        $this->timetracker_lib->getCurrentObj();

        if ($type_of_record=='record') $this->_record();
        elseif ($type_of_record=='categorie') $this->_categorie();
        elseif ($type_of_record=='tag') $this->_tag();
        else
        {
            $this->data[ 'current' ]['tab'] = $type_of_record;
            $this->data[ 'title' ]=$this->data[ 'activity' ][ 'type_of_record' ].': '.$this->data[ 'activity' ]['title'];



            $this->load->library('pagination');

            $config['base_url'] = site_url('tt/'.$username.'/'.$type_of_record.'/'.$activity_id);
            $config['total_rows'] = $this->records->get_records_count($this->user_id, array( 'activity'=>$activity_id, 'type_of_record'=>$type_of_record,    'datefrom' => $this->data[ 'current' ]['datefrom'], 'dateto' => $this->data[ 'current' ]['dateto'] ) );
            $config['uri_segment'] = 5; // autodetection dont work ???

            $this->pagination->initialize($config);

            $per_page=$this->pagination->per_page;
            $offset= ( $page-1 ) * $per_page;


            $this->data['list'][ $type_of_record.'_records' ]        = $this->records->get_records_full($this->user_id, array( 'activity'=>$activity_id, 'type_of_record'=>$type_of_record,    'datefrom' => $this->data[ 'current' ]['datefrom'], 'dateto' => $this->data[ 'current' ]['dateto'] ), $offset, $per_page );
            $this->data[ 'pager']               = $this->pagination->create_links();
            $this->data[ 'tt_layout' ]          = 'tt_activity';
        }

        $this->timetracker_lib->render();

    }


    public function generic_activity_new( $username, $type_of_record, $stopall=FALSE ) {
        $this->timetracker_lib->checkUsername( $username );
        $this->data[ 'current' ]['cat'] = $type_of_record;
        $this->data[ 'tt_layout' ] = 'tt_new';

        if ($stopall) $this->stop_all( $username, $type_of_record, FALSE );
                
        if ( $this->data['ajax'] ) {
                $this->load->view( 'timetracker/form/new_'.$type_of_record.'_form_ajax', $this->data );
        
        }
        else {
                
                $this->timetracker_lib->render();
        }

    }



    public function generic_activity_edit( $username, $type_of_record, $activity_id =NULL ) {
        $this->timetracker_lib->checkUsername( $username );

        $this->data[ 'current' ]['cat'] = $type_of_record;
        $this->data[ 'current' ]['id'] = $activity_id;
        $this->data[ 'current' ]['username'] = $username;
        
        $this->data[ 'modal' ] = $this->data[ 'ajax' ];        
        $this->data[ 'current' ]['subtitle'] = 'edit';

        $this->timetracker_lib->getCurrentObj();

        if ($type_of_record=='record') $this->_record_edit();
        elseif ($type_of_record=='categorie') $this->_categorie_edit();
        elseif ($type_of_record=='tag') $this->_tag_edit();
        else
        {

            $this->data[ 'title' ]=$this->data[ 'activity' ][ 'type_of_record' ].': '.$this->data[ 'activity' ]['title'];
            $this->data[ 'tt_layout' ] = 'tt_activity_edit';
        }
        $this->timetracker_lib->render();
    }

    public function generic_activity_delete( $username, $type_of_record, $activity_id =NULL ) {
        $this->timetracker_lib->checkUsername( $username );

        $this->data[ 'current' ]['cat'] = $type_of_record;
        $this->data[ 'current' ]['id'] = $activity_id;
        $this->data[ 'current' ]['username'] = $username;
        $this->data['subtitle'] = 'delete'; // FIXME

        $this->timetracker_lib->getCurrentObj();
        if ($type_of_record=='record')
            $this->_record_delete();
        else show_404();

        if ( $this->data['ajax'] ) {
                $this->data[ 'modal' ] = TRUE;
                $this->data[ 'tt_layout' ] = 'tt_record_delete_ajax';
                }
        
               
        $this->timetracker_lib->render();
    }




    /*****
     *  show record
     *  */
    public function _record( ) {
        $this->data[ 'tt_layout' ] = 'tt_record';
        $username=$this->data[ 'current' ]['username'];         
   }


    /*****
     *  edit record
     *  */
    public function _record_edit() {
        $this->data[ 'tt_layout' ] = 'tt_record_edit';
        $username=$this->data[ 'current' ]['username'];

    }

    /*****
     *  delete record
     *  */
    public function _record_delete() {

        $this->data[ 'tt_layout' ] = 'tt_record';
        $username=$this->data[ 'current' ]['username'];
        $record_id=$this->data[ 'current' ]['id'];
        
        $record= $this->records->get_record_by_id_full( $record_id );
        if ( !$record )
            show_404();


        $this->data[ 'record' ][ 'delete_confirm' ] = TRUE;
        $confirmed = $this->input->get( 'delete', TRUE );

        if ( $confirmed == 'true' ) {
            if ( $this->records->delete_record( $record_id ) ) {
                $alert = array(
                     array(
                        'type' => 'block',
                        'alert' => $record['activity']['type_of_record'] . ' record deleted ! "' . $record['activity']['activity_path'] . '"'
                    )
                );
                $this->session->set_flashdata( 'alerts', $alert );

            $this->timetracker_lib->redirect_type_of_record($record[ 'activity' ]['type_of_record']);
           
            }
        }

    }


    /*****
     *  show categorie
     *  */

    public function _categorie() {
        $categorie_id=$this->data[ 'current' ]['id'];
        $username=$this->data[ 'current' ]['username'];



        $list=array();
        $list[ 'count' ][ 'activity' ]    = $this->records->get_records_count($this->user_id, array( 'categorie'=>$categorie_id, 'type_of_record' => 'activity',    'datefrom' => $this->data[ 'current' ]['datefrom'], 'dateto' => $this->data[ 'current' ]['dateto'] ) );
        $list[ 'count' ][ 'todo' ]        = $this->records->get_records_count($this->user_id, array( 'categorie'=>$categorie_id, 'type_of_record' => 'todo',        'datefrom' => $this->data[ 'current' ]['datefrom'], 'dateto' => $this->data[ 'current' ]['dateto'] ) );
        $list[ 'count' ][ 'value' ]       = $this->records->get_records_count($this->user_id, array( 'categorie'=>$categorie_id, 'type_of_record' => 'value',       'datefrom' => $this->data[ 'current' ]['datefrom'], 'dateto' => $this->data[ 'current' ]['dateto'] ) );

        $this->load->library('pagination');

        $config['base_url'] = tt_url($username,'records',$this->data[ 'current' ],NULL, TRUE);
        $config['total_rows'] =$list[ 'count' ][ $this->data[ 'current' ]['tab'] ];
        $config['uri_segment'] = 5; // autodetection dont work ???

        $this->pagination->initialize($config);

        $per_page=$this->pagination->per_page;
        $offset= ( $this->data[ 'current' ]['page']-1 ) * $per_page;
      
        $list[ $this->data[ 'current' ]['tab'] . '_records'] = $this->records->get_records_full(
            $this->user_id,
            array(
                'categorie'=>$categorie_id,
                'type_of_record' => $this->data[ 'current' ]['tab'],
                'datefrom' => $this->data[ 'current' ]['datefrom'],
                'dateto' => $this->data[ 'current' ]['dateto']
                ),
            $offset,
            $per_page
            );

        $this->data['list']= $list;


        $this->data[ 'pager']                      = $this->pagination->create_links();
        $this->data[ 'tabs' ]                      = tabs_buttons ( $username, $this->data['current'], $list[ 'count' ] );
        $this->data[ 'tt_layout' ]                 = 'tt_categorie';

        $this->data[ 'TODO' ] = "categorie " . $categorie_id . " page - show shared status total time & activity";
    }


    /*****
     *  show categorie edit
     *  */

    public function _categorie_edit() {

        $categorie_id=$this->data[ 'current' ]['id'];
        $username=$this->data[ 'current' ]['username'];
    
        $this->data[ 'tt_layout' ]  = 'tt_categorie_edit';
        $this->data[ 'TODO' ]       = "categorie " . $categorie_id . "  add share function";

    }










    /*****
     *  show tag
     *  */

    public function _tag() {

        $tag_id=$this->data[ 'current' ]['id'];
        $username=$this->data[ 'current' ]['username'];

        $list=array();
        $list[ 'count' ][ 'activity' ]    = $this->records->get_records_count($this->user_id, array( 'tags'=> array($tag_id), 'type_of_record' => 'activity',   'datefrom' => $this->data[ 'current' ]['datefrom'], 'dateto' => $this->data[ 'current' ]['dateto'] ) );
        $list[ 'count' ][ 'todo' ]        = $this->records->get_records_count($this->user_id, array( 'tags'=> array($tag_id), 'type_of_record' => 'todo',       'datefrom' => $this->data[ 'current' ]['datefrom'], 'dateto' => $this->data[ 'current' ]['dateto'] ) );
        $list[ 'count' ][ 'value' ]       = $this->records->get_records_count($this->user_id, array( 'tags'=> array($tag_id), 'type_of_record' => 'value',      'datefrom' => $this->data[ 'current' ]['datefrom'], 'dateto' => $this->data[ 'current' ]['dateto'] ) );


        $this->load->library('pagination');

        $config['base_url'] = site_url('tt/'.$username.'/tag_'.$tag_id);
        $config['total_rows'] = $list[ 'count' ][ $this->data[ 'current' ]['tab'] ];
        $config['uri_segment'] = 5; // autodetection dont work ???

        $this->pagination->initialize($config);

        $per_page=$this->pagination->per_page;
        $offset= ( $this->data[ 'current' ]['page']-1 ) * $per_page;

        $this->pagination->initialize($config);

        $list[ $this->data[ 'current' ]['tab'] . '_records'] = $this->records->get_records_full(
            $this->user_id,
            array(
                'tags'=> array($tag_id),
                'type_of_record' => $this->data[ 'current' ]['tab'],
                'datefrom' => $this->data[ 'current' ]['datefrom'],
                'dateto' => $this->data[ 'current' ]['dateto']
                ),
            $offset,
            $per_page
            );

        $this->data['list']= $list;

        $this->data[ 'pager']                      = $this->pagination->create_links();
        $this->data[ 'tabs' ]                      = tabs_buttons ( $username, $this->data['current'], $list[ 'count' ] );
        $this->data[ 'tt_layout' ]                 = 'tt_tag';

        $this->data[ 'TODO' ] = "tag " . $tag_id . " page";

    }


    /*****
     *  show tag edit
     *  */

    public function _tag_edit( ) {

        $tag_id=$this->data[ 'current' ]['id'];
        $username=$this->data[ 'current' ]['username'];

        $this->data[ 'tt_layout' ]                 = 'tt_tag_edit';

    }


    /************
     * JSON
     * *************/
    
    
    public function json_activities( $username, $type_of_record ) {
        $this->output->enable_profiler( FALSE );
        
        $this->timetracker_lib->checkUsername( $username );
        $activities = $this->activities->getActivitiespathList( $this->user_id, $type_of_record );
        
        $content= json_encode($activities);
        $this->output
            ->set_content_type('application/json')
            ->set_output( $content );
    }
    
    public function json_tags( $username ) {
        $this->output->enable_profiler( FALSE );
        
        $this->timetracker_lib->checkUsername( $username );
        $tags = $this->tags->get_tag_list( $this->user_id );
        
        $content= json_encode($tags);
        $this->output
            ->set_content_type('application/json')
            ->set_output( $content );
    }





    /************
     * SETTINGS
     * *************/


    /*****
     *  settings page
     *  */

    public function settings( $username ) {
        $this->timetracker_lib->checkUsername( $username );
        $this->data[ 'current' ]['page'] = 'settings';
        $this->data[ 'current' ]['cat'] = NULL;
        $this->data[ 'current' ]['id']  = NULL;


        $this->data[ 'tt_layout' ]                 = 'tt_settings';

        $this->data[ 'TODO' ] = "add time gestion, language, hidden cat-tags...";
        $this->timetracker_lib->render();
    }







    /************
     * POST GESTION
     * *************/


    function _fromPOST( $post ) {
        $res = NULL;

        $this->load->library( 'form_validation' );

        if ( element( 'start', $post ) )
            $res = $this->_start_record( $post );

        if ( element( 'update_record', $post ) )
            $res = $this->_update_record( $post );

        if ( element( 'update_activity', $post ) )
            $res = $this->_update_activity( $post );

        if ( element( 'update_categorie', $post ) )
            $res = $this->_update_categorie( $post );

        if ( element( 'update_tag', $post ) )
            $res = $this->_update_tag( $post );

        if ( element( 'update_valuetype', $post ) )
            $res = $this->_update_valuetype( $post );

        if ( element( 'param_timezone', $post ) )
            $res = $this->_update_params( $post );

        return $res;
    }



    function _start_record( $post ) {
        $this->form_validation->set_rules( 'start', 'Activity', 'trim|required' );
        
        if ($post[ 'type_of_record' ]=='value')
                $this->form_validation->set_rules( 'value', 'value', 'callback_value_check' );

        $res= array();

        if ( $this->form_validation->run() === TRUE ) {

            $param  = array( );

            if (isset($post[ 'type_of_record' ]))
                $type_record = $post[ 'type_of_record' ];
            else
                $type_record = 'activity';

            if ( $post[ 'start' ][ 0 ] == '!' )
                $type_record = 'todo';
            if ( $post[ 'start' ][ 0 ] == '.' )
                $param[ 'running' ] = 0; // ping
            if ( element( 'value', $post ) OR ($post[ 'start' ][ 0 ] == '$') ) 
                $param[ 'running' ] = 0;


            preg_match( '/\[{1}.+\]{1}/i', $post[ 'start' ], $path_tags ); // get tags from path
            if ( ( $path_tags ) && ( !element( 'tags', $post ) ) )
                $post[ 'tags' ] = trim( $path_tags[ 0 ], '[] ' );

            if ( element( 'tags', $post ) )
                $tags = preg_split( '/,/', $post[ 'tags' ], -1, PREG_SPLIT_NO_EMPTY ); // get tags from input

            $post[ 'start' ] = preg_replace( '/(\!|\.|\[{1}.+\]{1})*/i', '', $post[ 'start' ] ); // clean activity path phase1

            if ( $type_record != 'value' ) {
                preg_match( '/\${1}.+\={1}.+/i', $post[ 'start' ], $path_value ); // get value from path
                if ( $path_value ) {
                    $path_value_array     = preg_split( '/=/', $path_value[ 0 ], -1, PREG_SPLIT_NO_EMPTY );
                    $post[ 'start' ]      = trim( $path_value_array[ 0 ], '$ ' );
                    $post[ 'value' ]      = trim( $path_value_array[ 1 ] );
                    $type_record          = 'value';
                    $param[ 'running' ]   = 0;
                }
            }

            $post[ 'start' ] = preg_replace( '/\${1}.+\={1}.+/i', '', $post[ 'start' ] ); // clean activity path phase2

            if ( strpos( $post[ 'start' ], '@' ) === FALSE ) {
                $categorie  = '';
                $title = trim( $post[ 'start' ] );
            }
            else {
                $split = preg_split( '/@/', $post[ 'start' ], -1, PREG_SPLIT_NO_EMPTY );
                $categorie  = trim( $split[ 1 ] );
                $title = trim( $split[ 0 ] );
            }

            if ( isset( $post[ 'description' ] ) )
                $param[ 'description' ] = trim( $post[ 'description' ] );

            $res[ 'activity' ] = $this->_create_record( $title, $categorie, $type_record, $param );
            $res[ 'alerts' ]   = array(
                 array(
                     'type' => 'success',
                    'alert' => 'start new ' . $type_record . ' record: "' . $res[ 'activity' ][ 'activity_path' ] . '"'
                )
            );
            
            if ( $type_record == 'activity' )
            {
                    if ($this->user_params['startactivitymode']=='one_at_the_time') {
                        $records= $this->records->get_records($this->user_id, array('type_of_record'=>'activity', 'running'=>true));
                        foreach ($records as $r)   
                                if ($r['id']!=$res[ 'activity' ][ 'record' ][ 'id' ])
                                        $this->records->stop_record( $r['id'] );
                    }            
                    elseif ($this->user_params['startactivitymode']=='one_by_categorie') {
                        $records= $this->records->get_records($this->user_id, array('type_of_record'=>'activity', 'running'=>true, 'categorie'=>$res[ 'activity' ]['categorie_ID']));
                        foreach ($records as $r)   
                                  if ($r['id']!=$res[ 'activity' ][ 'record' ][ 'id' ])
                                        $this->records->stop_record( $r['id'] );
                    }  
            }          
            
            
            if ( isset( $tags ) )
                foreach ( $tags as $k => $tag )
                    $this->tags->add_tag_record( $this->user_id, $res[ 'activity' ][ 'record' ][ 'id' ], trim( $tag ) ); // add tags

            if ( element( 'value', $post ) )
                $this->values->add_value(  $res[ 'activity' ][ 'record' ][ 'id' ], $post[ 'value' ] ); // add value

            $this->session->set_flashdata( 'alerts', $res[ 'alerts' ] );

            $this->timetracker_lib->redirect_type_of_record( $type_record );         
            
                    
        }
        else {
            $res[ 'alerts' ]   = array(
                 array(
                     'type' => 'error',
                    'alert' => 'error ' //TODO! tester
                )
            );
        }

        return $res;
    }





    function _update_record( $post ) {
        $this->form_validation->set_rules( 'update_record', 'Record id', 'required|integer' );
        $this->form_validation->set_rules( 'activity', 'Activity', 'trim|required' );
        $this->form_validation->set_rules( 'start_time', 'Start time', 'required' );
        if ($post[ 'type_of_record' ]=='value')
                $this->form_validation->set_rules( 'value', 'value', 'callback_value_check' );
       

         $res= array();

        if ( $this->form_validation->run() === TRUE ) {

            $param  = array( );
            if (isset($post[ 'type_of_record' ]))
                $type_record = $post[ 'type_of_record' ];
            else
                $type_record = 'activity';

            if ( $post[ 'activity' ][ 0 ] == '!' )
                $type_record = 'todo';
            if ( $post[ 'activity' ][ 0 ] == '.' )
                $param[ 'running' ] = 0; // ping
            if ( element( 'value_name', $post ) ) {
                $type_record        = 'value';
                $param[ 'running' ] = 0;
            }


            if ( strpos( $post[ 'activity' ], '@' ) === FALSE ) {
                $categorie  = '';
                $title = trim( $post[ 'activity' ] );
            }
            else {
                $split = preg_split( '/@/', $post[ 'activity' ], -1, PREG_SPLIT_NO_EMPTY );
                $categorie  = trim( $split[ 1 ] );
                $title = trim( $split[ 0 ] );
            }

            if ( isset( $post[ 'description' ] ) )
                $param[ 'description' ] = trim( $post[ 'description' ] );


            $cat = $this->categories->getorcreate_categorie( $this->user_id, $categorie );

            $res ['activity'] = $this->activities->getorcreate_activity( $cat[ 'id' ], $title, $type_record );
            $res ['activity'] = $this->activities->get_activity_by_id_full(  $res['activity']['id'] );

            $update_params=array(
                     'description' => $post[ 'description' ],
                    'start_time' => $post[ 'start_time' ],
                    'activity_ID' => $res ['activity']['id']
                );
                
            if ( element( 'tags', $post ) )
                $tags = preg_split( '/,/', $post[ 'tags' ], -1, PREG_SPLIT_NO_EMPTY ); // get tags from input

            if ( isset( $post[ 'duration' ] ) ) $update_params['duration'] = $post[ 'duration' ];
            if ( isset( $post[ 'running' ] ) ) $update_params['running'] = $post[ 'running' ];

            $this->records->update_record( $post[ 'update_record' ], $update_params);
            $res[ 'activity' ][ 'record' ] = $this->records->get_record_by_id( $post[ 'update_record' ] );


            $this->tags->reset_record_tags( $post[ 'update_record' ] );

            if ( isset( $tags ) )
                foreach ( $tags as $k => $tag )
                    $this->tags->add_tag_record( $this->user_id, $res[ 'activity' ][ 'record' ][ 'id' ], trim( $tag ) ); // add tags

            if ( element( 'value', $post ) )
                $this->values->update_value(  $res[ 'activity' ][ 'record' ][ 'id' ], $post[ 'value' ] ); // add value

            $res[ 'alerts' ]   = array(
                 array(
                     'type' => 'info',
                    'alert' => 'update ' . $res[ 'activity' ][ 'type_of_record' ] . ' record: "' . $res[ 'activity' ][ 'activity_path' ] . '"'
                )
            );
            $this->session->set_flashdata( 'alerts', $res[ 'alerts' ] );
            //redirect( 'tt/' . $this->user_name . '/record_'.$res[ 'activity' ][ 'record' ][ 'id' ], 'location' );
            $this->timetracker_lib->redirect_type_of_record( $res[ 'activity' ][ 'type_of_record' ] );
        }
        else {
            $res[ 'alerts' ]   = array(
                 array(
                     'type' => 'error',
                    'alert' => 'error ' //TODO! tester
                )
            );
        }

        return $res;
    }


    function value_check($str)
    {

       if (preg_match("/^((\d|\-|\.)+)$/i", $str)) return TRUE;

       if (preg_match("/^((\d|\-|\.)+){1}(,[ ]((\d|\-|\.)+))*$/i", $str)) return TRUE;

       $this->form_validation->set_message('value_check', 'The %s field should be a numeric or a group of numeric seperate by a comma (ie. 12.5,15.5 ) ');
       return FALSE;
    }


    function _create_record( $title, $categorie = '', $type_record, $param = array( ) ) {
        $cat = $this->categories->getorcreate_categorie( $this->user_id, $categorie );
        if ( isset( $param[ 'tags' ] ) ) {
            $tags = $param[ 'tags' ];
            unset( $param[ 'tags' ] );
        }

        if ( isset( $param[ 'values' ] ) ) {
            $values = $param[ 'values' ];
            unset( $param[ 'values' ] );
        }

        $activity = $this->activities->getorcreate_activity( $cat[ 'id' ], $title, $type_record );

        $activity[ 'record' ] = $this->records->create_record( $activity[ 'id' ], $param );

        return $activity;
    }


     function _update_activity( $post ) {

        $this->actual_activity= $this->activities->get_activity_by_id( $post[ 'update_activity' ] );
        $this->form_validation->set_rules( 'update_activity', 'Activity id', 'required|integer' );
        $this->form_validation->set_rules( 'activity', 'Activity', 'callback__activityname_check|trim|required' ); //TODO trim ponctuation


        $res= array();

        if ( $this->form_validation->run() === TRUE ) {

            $param  = array( 'description' => $post[ 'description' ] );


            if ( strpos( $post[ 'activity' ], '@' ) === FALSE ) {
                $categorie  = '';
                $param['title']= trim( $post[ 'activity' ] );
            }
            else {
                $split = preg_split( '/@/', $post[ 'activity' ], -1, PREG_SPLIT_NO_EMPTY );
                $categorie  = trim( $split[ 1 ] );
                $param['title'] = trim( $split[ 0 ] );
            }


            $categorie = $this->categories->getorcreate_categorie( $this->user_id, $categorie );
            $param['categorie_ID'] = $categorie['id'];

            $this->activities->update_activity( $post[ 'update_activity' ], $param);
            $res[ 'activity' ]= $this->activities->get_activity_by_id( $post[ 'update_activity' ] );

            $res[ 'alerts' ]   = array(
                 array(
                     'type' => 'info',
                    'alert' => 'update ' . $res[ 'activity' ][ 'type_of_record' ] . ': "' . $res[ 'activity' ][ 'activity_path' ] . '"'
                )
            );
            $this->session->set_flashdata( 'alerts', $res[ 'alerts' ] );
            //redirect( 'tt/' . $this->user_name . '/'.$res[ 'activity' ]['type_of_record'].'/'.$res[ 'activity' ][ 'id' ], 'location' );
            $this->timetracker_lib->redirect_type_of_record($res[ 'activity' ]['type_of_record']);

        }
        else {
            $res[ 'alerts' ]   = array(
                 array(
                     'type' => 'error',
                    'alert' => 'error ' //TODO! tester
                )
            );
        }

        return $res;
    }




    public function _activityname_check($str)
    {
        if ( strpos( $str, '@' ) === FALSE ) {
                $categorie_title  = '';
                $title= trim( $str );
            }
        else {
                $split = preg_split( '/@/', $str, -1, PREG_SPLIT_NO_EMPTY );
                $categorie_title  = trim( $split[ 1 ] );
                $title = trim( $split[ 0 ] );
            }
         $categorie = $this->categories->getorcreate_categorie( $this->user_id, $categorie_title );


        $act = $this->activities->get_activity(  $categorie['id'], $title, $this->actual_activity['type_of_record'] );
        if ( ( $act === NULL ) OR ( $act['id'] ==  $this->actual_activity['id'] ) )
        {
            return TRUE;
        }
        else        {

            $this->form_validation->set_message('_activityname_check', $this->actual_activity['type_of_record'].' %s named \''. trim($str) .'\' already exists');
            return FALSE;
        }
    }






    function _update_categorie( $post ) {

        $this->data['update_categorie'] = $post[ 'update_categorie' ];

        $this->form_validation->set_rules( 'update_categorie', 'Categorie id', 'required|integer' );
        $this->form_validation->set_rules( 'categorie', 'Categorie', 'callback__categoriename_check|trim' );

        if (!isset($post['isshown'])) $post['isshown']=0;

        $res= array();

        if ( $this->form_validation->run() === TRUE ) {

            $param  = array( 'description' => $post[ 'description' ], 'title' => $post['categorie'], 'isshown' => $post['isshown'] );

            $this->categories->update_categorie( $post[ 'update_categorie' ], $param);
            $res[ 'categorie' ]= $this->categories->get_categorie_by_id( $post[ 'update_categorie' ] );

            $res[ 'alerts' ]   = array(
                 array(
                     'type' => 'info',
                    'alert' => 'update categorie: "' . $res[ 'categorie' ][ 'title' ] . '"'
                )
            );

            $this->session->set_flashdata( 'alerts', $res[ 'alerts' ] );
            redirect( 'tt/' . $this->user_name . '/categorie_'.$res[ 'categorie' ][ 'id' ], 'location' );
        }
        else {
            $res[ 'alerts' ]   = array(
                 array(
                     'type' => 'error',
                    'alert' => 'error ' //TODO! tester
                )
            );

        }

        return $res;
    }


    public function _categoriename_check($str)
    {
        $cat = $this->categories->get_categorie_by_title( $this->user_id, trim($str) );
        if ( ( $cat === NULL ) OR ( $cat['id'] == $this->data['update_categorie'] ) )
        {
            return TRUE;
        }
        else
        {
            $this->form_validation->set_message('_categoriename_check', '%s named \''. trim($str) .'\' already exists');
            return FALSE;
        }
    }




    function _update_tag( $post ) {

        $this->data['update_tag'] = $post[ 'update_tag' ];

        $this->form_validation->set_rules( 'update_tag', 'Tag id', 'required|integer' );
        $this->form_validation->set_rules( 'tag', 'Tag', 'callback__tagname_check|trim' );

        if (!isset($post['isshown'])) $post['isshown']=0;

        $res= array();

        if ( $this->form_validation->run() === TRUE ) {

            $param  = array( 'tag' => $post['tag'], 'isshown' => $post['isshown'] );

            $this->tags->update_tag( $post[ 'update_tag' ], $param);
            $res[ 'tag' ]= $this->tags->get_tag_by_id( $post[ 'update_tag' ] );

            $res[ 'alerts' ]   = array(
                 array(
                     'type' => 'info',
                    'alert' => 'update tag: "' . $res[ 'tag' ][ 'tag' ] . '"'
                )
            );

            $this->session->set_flashdata( 'alerts', $res[ 'alerts' ] );
            redirect( 'tt/' . $this->user_name . '/tag_'.$res[ 'tag' ][ 'id' ], 'location' );
        }
        else {
            $res[ 'alerts' ]   = array(
                 array(
                     'type' => 'error',
                    'alert' => 'error ' //TODO! tester
                )
            );

        }

        return $res;
    }


    public function _tagname_check($str)
    {
        $cat = $this->tags->get_tag( $this->user_id, trim($str) );
        if ( ( $cat === NULL ) OR ( $cat['id'] == $this->data['update_tag'] ) )
        {
            return TRUE;
        }
        else
        {
            $this->form_validation->set_message('_tagname_check', '%s \''. trim($str) .'\' already exists');
            return FALSE;
        }
    }








    function _update_params( $post ) {

        //$this->load->model( 'tank_auth/users' );

        foreach( $post as $pst_key => $post_val )
             if ( preg_match( '/^(param_)/', $pst_key ))
                $params[ trim( $pst_key , 'param_') ] = $post_val;

        $this->users->update_params( $this->user_id, json_encode($params) );


        $res[ 'alerts' ]   = array(
            array(
                'type' => 'info',
                'alert' => 'user params updated'
            )
        );

        $this->session->set_flashdata( 'alerts', $res[ 'alerts' ] );
        redirect( 'tt/' . $this->user_name , 'location' );
    }






}

/* End of file test.php */
/* Location: ./application/controllers/timetracker.php */
