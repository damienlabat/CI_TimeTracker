<?php
if ( !defined( 'BASEPATH' ) )
    exit( 'No direct script access allowed' );

class Timetracker extends CI_Controller {
    function __construct( ) {
        parent::__construct();

        $this->output->enable_profiler(TRUE);

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

        $this->data[ 'tt_layout' ] = 'tt_home';

        $count = $this->config->item('headerbloc_perpage');
        $this->data[ 'last_activities' ] =  $this->records->get_records_full($this->user_id, array( 'type_of_record' => 'activity', 'running' => 0 ) ,0 , $count );
        $this->data[ 'last_values' ] =      $this->records->get_records_full($this->user_id, array( 'type_of_record' => 'value' ) ,0 , $count );

        $this->timetracker_lib->render();
    }


    public function activities( $username = NULL ) {

        $this->timetracker_lib->checkUsername( $username );

        $page = isset($_GET['page']) ? $_GET['page']: 1;

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

        $record= $this->records->get_record_by_id( $record_id );
        if ( !$record )
            show_404();

        $stopped = $this->records->stop_record( $record_id );

        if ( isset( $stopped[ 'alerts' ] ) )
            $this->session->set_flashdata( 'alerts', $stopped[ 'alerts' ] );
        redirect( 'tt/' . $username, 'location' );
    }












    /*****
     *  restart record
     *  */
    public function restart( $username, $record_id ) {
        $this->timetracker_lib->checkUsername( $username );

        $this->data[ 'current' ]['cat'] = 'categorie';
        $this->data[ 'current' ]['id'] = NULL;
        $this->timetracker_lib->getCurrentObj();

        if ( $this->records->restart_record( $record_id ) )
            $alert = array(
                 array(
                     'type' => 'success',
                    'alert' => 'start new record !'
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
        redirect( 'tt/' . $username, 'location' );

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
            $this->data[ 'breadcrumb' ][]=  array( 'title'=> 'home',                                                'url'=>tt_url($username,'record',$this->data[ 'current' ], array('id'=>NULL)) );
            $this->data[ 'breadcrumb' ][]=  array( 'title'=> format_categorie($this->data[ 'activity' ]['categorie']),   'url'=>tt_url($username,'record',$this->data[ 'current' ], array('cat'=>'categorie','id'=>$this->data[ 'activity' ]['categorie_ID'])) );
            $this->data[ 'breadcrumb' ][]=  array( 'title'=> $this->data[ 'activity' ]['title'],                    'url'=>tt_url($username,'record',$this->data[ 'current' ], array('cat'=>$this->data[ 'activity' ]['type_of_record'],'id'=>$this->data[ 'activity' ]['id'])) );
            $this->data[ 'title' ]=$this->data[ 'activity' ][ 'type_of_record' ].': '.$this->data[ 'activity' ]['title'];



            $this->load->library('pagination');

            $config['base_url'] = site_url('tt/'.$username.'/'.$type_of_record.'/'.$activity_id);
            $config['total_rows'] = $this->records->get_records_count($this->user_id, array( 'activity'=>$activity_id, 'type_of_record'=>$type_of_record ) );
            $config['uri_segment'] = 5; // autodetection dont work ???

            $this->pagination->initialize($config);

            $per_page=$this->pagination->per_page;
            $offset= ( $page-1 ) * $per_page;


            $this->data['list'][ $type_of_record.'_records' ]        = $this->records->get_records_full($this->user_id, array( 'activity'=>$activity_id, 'type_of_record'=>$type_of_record ), $offset, $per_page );
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
        $this->timetracker_lib->render();

    }



    public function generic_activity_edit( $username, $type_of_record, $activity_id =NULL ) {
        $this->timetracker_lib->checkUsername( $username );

        $this->data[ 'current' ]['cat'] = $type_of_record;
        $this->data[ 'current' ]['id'] = $activity_id;
        $this->data[ 'current' ]['username'] = $username;

        $this->timetracker_lib->getCurrentObj();

        if ($type_of_record=='record') $this->_record_edit();
        elseif ($type_of_record=='categorie') $this->_categorie_edit();
        elseif ($type_of_record=='tag') $this->_tag_edit();
        else
        {

            $this->data[ 'breadcrumb' ][]=  array( 'title'=> 'home', 'url'=>tt_url($username,'record',$this->data[ 'current' ], array('id'=>NULL)) );
            if ($this->data[ 'activity' ]['categorie']['title']!='')
                $this->data[ 'breadcrumb' ][]=  array( 'title'=> $this->data[ 'activity' ]['categorie']['title'],   'url'=>tt_url($username,'record',$this->data[ 'current' ], array('cat'=>'categorie','id'=>$this->data[ 'activity' ]['categorie_ID'])) );
            $this->data[ 'breadcrumb' ][]=  array( 'title'=> $this->data[ 'activity' ]['title'],                    'url'=>tt_url($username,'record',$this->data[ 'current' ], array('cat'=>$this->data[ 'activity' ]['type_of_record'],'id'=>$this->data[ 'activity' ]['id'])) );
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

        $this->timetracker_lib->getCurrentObj();
        if ($type_of_record=='record')
            $this->_record_delete();
        else show_404();

        $this->timetracker_lib->render();
    }




    /*****
     *  show record
     *  */
    public function _record( ) {
        $this->data[ 'tt_layout' ] = 'tt_record';
        $username=$this->data[ 'current' ]['username'];

        $this->data[ 'breadcrumb' ]= array(
            array( 'title'=> 'home',                                                        'url'=>tt_url($username,'record',$this->data[ 'current' ], array('id'=>NULL)) ),
            array( 'title'=> format_categorie($this->data[ 'record' ][ 'activity' ]['categorie']),   'url'=>tt_url($username,'record',$this->data[ 'current' ], array('cat'=>'categorie', 'id'=>$this->data[ 'record' ][ 'activity' ]['categorie_ID'])) ),
            array( 'title'=> $this->data[ 'record' ][ 'activity' ]['title'],                'url'=>tt_url($username,'record',$this->data[ 'current' ], array('cat'=>$this->data[ 'record' ][ 'activity' ]['type_of_record'],'id'=>$this->data[ 'record' ][ 'activity' ]['id'])) ),
            array( 'title'=> 'start at : '.$this->data[ 'record' ][ 'start_time' ],         'url'=>tt_url($username,'record',$this->data[ 'current' ]))
            );
   }


    /*****
     *  edit record
     *  */
    public function _record_edit() {
        $this->data[ 'tt_layout' ] = 'tt_record_edit';
        $username=$this->data[ 'current' ]['username'];

        $this->data[ 'breadcrumb' ]= array(
            array( 'title'=> 'categorie : '.$this->data[ 'record' ][ 'activity' ]['categorie']['title'],                                                  'url'=>tt_url($username,'record',$this->data[ 'current' ], array('cat'=>'categorie', 'id'=>$this->data[ 'record' ][ 'activity' ]['categorie_ID'])) ),
            array( 'title'=> $this->data[ 'record' ][ 'activity' ]['type_of_record'].' : '.$this->data[ 'record' ][ 'activity' ]['title'],                'url'=>tt_url($username,'record',$this->data[ 'current' ], array('cat'=>$this->data[ 'record' ][ 'activity' ]['type_of_record'],'id'=>$this->data[ 'record' ][ 'activity' ]['id'])) ),
            array( 'title'=> 'start at : '.$this->data[ 'record' ][ 'start_time' ],                                                                       'url'=>tt_url($username,'record',$this->data[ 'current' ]))
            );


    }

    /*****
     *  delete record
     *  */
    public function _record_delete() {

        $this->data[ 'tt_layout' ] = 'tt_record';
        $username=$this->data[ 'current' ]['username'];
        $record_id=$this->data[ 'current' ]['id'];


        $this->data[ 'record' ][ 'delete_confirm' ] = TRUE;
        $confirmed = $this->input->get( 'delete', TRUE );

        if ( $confirmed == 'true' ) {
            if ( $this->records->delete_record( $record_id ) ) {
                $alert = array(
                     array(
                        'type' => 'success',
                        'alert' => 'record deleted !'
                    )
                );
                $this->session->set_flashdata( 'alerts', $alert );
                redirect( 'tt/' . $username, 'location' );
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


        $this->data[ 'breadcrumb' ][]=  array( 'title'=> 'home','url'=>tt_url($username,'record',$this->data[ 'current' ], array('id'=>NULL )) );

        if ( $this->data[ 'categorie' ]['title']=='')
            $this->data[ 'breadcrumb' ][]= array( 'title'=> '(no categorie)', 'url'=>tt_url($username,'record',$this->data[ 'current' ]) );
        elseif ( $this->data[ 'categorie' ]['id']!=NULL)
            $this->data[ 'breadcrumb' ][]= array( 'title'=> $this->data[ 'categorie' ]['title'], 'url'=>tt_url($username,'record',$this->data[ 'current' ]));

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

        $this->data[ 'breadcrumb' ] = array(
            array( 'title'=> 'home','url'=>tt_url($username,'record',$this->data[ 'current' ], array('id'=>NULL)) ),
            array( 'title'=> $this->data[ 'categorie' ]['title'], 'url'=>site_url('tt/'.$username.'/categorie_'.$categorie_id)),
            array( 'title'=> 'edit', 'url'=>site_url('tt/'.$username.'/categorie_'.$categorie_id.'/edit'))
            );
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


        $this->data[ 'breadcrumb' ] = array(
            array( 'title'=> 'home',                                                'url'=>tt_url($username,'record',$this->data[ 'current' ], array('id'=>NULL)) ),
            array( 'title'=> 'tag: '.$this->data[ 'tag' ]['tag'], 'url'=>'')
            );

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

        $this->data[ 'breadcrumb' ] = array(
            array( 'title'=> 'home',   'url'=>tt_url($username,'record',$this->data[ 'current' ], array('id'=>NULL)) ),
            array( 'title'=> 'tag: '.$this->data[ 'tag' ]['tag'], 'url'=>'tt/'.$username.'/tag_'.$tag_id),
            array( 'title'=> 'edit', 'url'=>'tt/'.$username.'/tag_'.$tag_id.'/edit')
            );

        $this->data[ 'tt_layout' ]                 = 'tt_tag_edit';

    }






    /************
     * SETTINGS
     * *************/


    /*****
     *  settings page
     *  */

    public function settings( $username ) {
        $this->timetracker_lib->checkUsername( $username );
        $this->data[ 'current' ]['cat'] = NULL;
        $this->data[ 'current' ]['id']  = NULL;

        $this->data[ 'breadcrumb' ] = array(
            array( 'title'=> 'home',        'url'=>tt_url($username,'record',$this->data[ 'current' ], array('id'=>NULL)) ),
            array( 'title'=> 'settings',    'url'=>'tt/'.$username.'/settings' )
            );

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
            if ( element( 'value_name', $post ) ) {
                $type_record        = 'value';
                $param[ 'running' ] = 0;
            }

            preg_match( '/\[{1}.+\]{1}/i', $post[ 'start' ], $path_tags ); // get tags from path
            if ( ( $path_tags ) && ( !element( 'tags', $post ) ) )
                $post[ 'tags' ] = trim( $path_tags[ 0 ], '[] ' );

            if ( element( 'tags', $post ) )
                $tags = preg_split( '/,/', $post[ 'tags' ], -1, PREG_SPLIT_NO_EMPTY ); // get tags from input

            $post[ 'start' ] = preg_replace( '/(\!|\.|\[{1}.+\]{1})*/i', '', $post[ 'start' ] ); // clean activity path phase1

            if ( $type_record != 'value' ) {
                preg_match( '/\#{1}.+\={1}.+/i', $post[ 'start' ], $path_value ); // get value from path
                if ( $path_value ) {
                    $path_value_array     = preg_split( '/=/', $path_value[ 0 ], -1, PREG_SPLIT_NO_EMPTY );
                    $post[ 'value_name' ] = trim( $path_value_array[ 0 ], '# ' );
                    $post[ 'value' ]      = trim( $path_value_array[ 1 ] );
                    $type_record          = 'value';
                    $param[ 'running' ]   = 0;
                }
            }

            $post[ 'start' ] = preg_replace( '/\#{1}.+\={1}.+/i', '', $post[ 'start' ] ); // clean activity path phase2

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
                    'alert' => 'start new activity: ' . $res[ 'activity' ][ 'title' ]
                )
            );
            
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
            
            
            if ( isset( $tags ) )
                foreach ( $tags as $k => $tag )
                    $this->tags->add_tag_record( $this->user_id, $res[ 'activity' ][ 'record' ][ 'id' ], trim( $tag ) ); // add tags

            if ( element( 'value', $post ) )
                $this->values->add_value(  $res[ 'activity' ][ 'record' ][ 'id' ], $post[ 'value' ] ); // add value

            $this->session->set_flashdata( 'alerts', $res[ 'alerts' ] );
            redirect( 'tt/' . $this->user_name , 'location' );
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

            $update_params=array(
                     'description' => $post[ 'description' ],
                    'start_time' => $post[ 'start_time' ],
                    'activity_ID' => $res ['activity']['id']
                );

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
                     'type' => 'success',
                    'alert' => 'update activity: ' . $res[ 'activity' ][ 'title' ]
                )
            );
            $this->session->set_flashdata( 'alerts', $res[ 'alerts' ] );
            redirect( 'tt/' . $this->user_name . '/record_'.$res[ 'activity' ][ 'record' ][ 'id' ], 'location' );
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

       if (preg_match("/^\[((\d|\-|\.)+){1}(,((\d|\-|\.)+))*]$/i", $str)) return TRUE;

       $this->form_validation->set_message('value_check', 'The %s field should be a numeric or a group of numeric (ie. [12.5,15.5] ) ');
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
                     'type' => 'success',
                    'alert' => 'update activity: ' . $res[ 'activity' ][ 'title' ]
                )
            );
            $this->session->set_flashdata( 'alerts', $res[ 'alerts' ] );
            redirect( 'tt/' . $this->user_name . '/'.$res[ 'activity' ]['type_of_record'].'/'.$res[ 'activity' ][ 'id' ], 'location' );

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
                     'type' => 'success',
                    'alert' => 'update categorie: ' . $res[ 'categorie' ][ 'title' ]
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
                     'type' => 'success',
                    'alert' => 'update tag: ' . $res[ 'tag' ][ 'tag' ]
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

        $this->users->update_timezone($this->user_id, $post['timezone']);

        //$params=array();
        foreach( $post as $pst_key => $post_val )
             if ( preg_match( '/^(param_)/', $pst_key ))
                $params[ trim( $pst_key , 'param_') ] = $post_val;

        $this->users->update_params( $this->user_id, json_encode($params) );


        $res[ 'alerts' ]   = array(
            array(
                'type' => 'success',
                'alert' => 'user params updated'
            )
        );

        $this->session->set_flashdata( 'alerts', $res[ 'alerts' ] );
        redirect( 'tt/' . $this->user_name , 'location' );
    }






}

/* End of file test.php */
/* Location: ./application/controllers/timetracker.php */
