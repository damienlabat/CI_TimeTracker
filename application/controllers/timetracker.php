<?php
if ( !defined( 'BASEPATH' ) )
    exit( 'No direct script access allowed' );

class Timetracker extends CI_Controller {
    function __construct( ) {
        parent::__construct();
        $this->output->enable_profiler( TRUE );

        $this->load->helper( array(
             'url',
            'assets_helper',
            'form',
            'timetracker',
            'date',
            'array'
        ) );

        $this->load->library( 'tank_auth' );

        $this->load->model( array(
             'timetracker/categories',
            'timetracker/activities',
            'timetracker/tags',
            'timetracker/values',
            'timetracker/records'
        ) );

        $this->user_id   = $this->tank_auth->get_user_id();
        $this->user_name = $this->tank_auth->get_username();

        $this->data[ 'alerts' ] = array( );


        if ( $this->session->flashdata( 'alerts' ) )
            $this->data[ 'alerts' ] = $this->session->flashdata( 'alerts' ); //array( array('type'=>'success', 'alert'=>'error 1 .....') );


        if ( !$this->tank_auth->is_logged_in() ) {
            $this->_goLogin();
        }
        else {
            $this->data[ 'user_name' ] = $this->user_name;
            $this->data[ 'user_id' ]   = $this->user_id;

        }

        if ( $_POST ) {
            $res = $this->_fromPOST( $_POST );
            if ( isset( $res[ 'alerts' ] ) )
                $this->data[ 'alerts' ] = array_merge( $this->data[ 'alerts' ], $res[ 'alerts' ] );
        }

    }




    /* ==========================
     *  rendering & redirection
     * ========================== */

    public function _render( ) {
        $this->data[ 'content' ] = $this->load->view( 'timetracker/layout', $this->data, true );
        $this->load->view( 'layout', $this->data );
    }

    public function _goLogin( ) {
        redirect( 'login', 'location', 301 );
    }

    public function _checkUsername( $username ) {
        if ( $username != $this->data[ 'user_name' ] )
            $this->_goLogin(); //TODO shared folder gestion
    }

    public function _checkRecordType( $record_id, $type_of_record ) {
        // recup type

        //if ($type_of_record!='tracking') show_404();  //TODO controlle du type
    }



    /* ==========================
     *  actions
     * ========================== */


    /******
     * tt board
     * */

    public function index( $username = NULL, $page=1 ) {
        $per_page = 20;
        $offset= ( $page-1 ) * $per_page;
        $this->_checkUsername( $username );

        $this->load->library('pagination');


        $config['base_url'] = site_url('tt/'.$username.'/');
        $config['total_rows'] = $this->records->get_last_records_count( $this->user_id );
        $config['per_page'] = $per_page;

        $this->pagination->initialize($config);


        $this->data[ 'tt_layout' ]          = 'tt_board';
        $this->data[ 'running_activities' ] = $this->records->get_running_activities_full( $this->user_id );
        $this->data[ 'todos' ]              = $this->records->get_running_TODO_full( $this->user_id );
        $this->data[ 'last_actions' ]       = $this->records->get_last_actions_full( $this->user_id, NULL, NULL, $offset ,$per_page );
        $this->data[ 'pager']               = $this->pagination->create_links();

        $this->_render();
    }



    /*****
     *  stop record
     *  */
    public function stop( $username, $record_id ) {
        $this->_checkUsername( $username );

        $this->data[ 'record' ] = $this->records->get_record_by_id( $record_id );
        if ( !$this->data[ 'record' ] )
            show_404();

        $stopped = $this->records->stop_record( $record_id );

        if ( isset( $stopped[ 'alerts' ] ) )
            $this->session->set_flashdata( 'alerts', $stopped[ 'alerts' ] );
        redirect( 'tt/' . $username, 'location' );
    }


    /*****
     *  show record
     *  */
    public function record( $username, $record_id ) {
        $this->_checkUsername( $username );
        $this->data[ 'tt_layout' ] = 'tt_record';
        $this->data[ 'record' ]    = $this->records->get_record_by_id_full( $record_id );
        if ( !$this->data[ 'record' ] )
            show_404();
        $this->data[ 'categories' ]   = $this->categories->get_categories( $this->user_id );
        $this->data[ 'activities' ] = $this->activities->get_categorie_activities( $this->data[ 'record' ][ 'activity' ][ 'categorie_ID' ] );
        //print_r($this->data);
        $this->_render();
    }


    /*****
     *  edit record
     *  */
    public function edit_record( $username, $record_id ) {
        $this->_checkUsername( $username );
        // TODO!
        $this->data[ 'record' ] = $this->records->get_record_by_id_full( $record_id );
        if ( !$this->data[ 'record' ] )
            show_404();
        $this->data[ 'tt_layout' ] = 'tt_record_edit';

        $this->data[ 'TODO' ] = "edit record " . $record_id;
        $this->_render();
    }


    /*****
     *  delete record
     *  */
    public function delete_record( $username, $record_id ) {
        $this->_checkUsername( $username );
        $this->data[ 'tt_layout' ] = 'tt_record';
        $this->data[ 'record' ]    = $this->records->get_record_by_id_full( $record_id );
        if ( !$this->data[ 'record' ] )
            show_404();
        $this->data[ 'record' ][ 'delete_confirm' ] = TRUE;
        $this->data[ 'categories' ]                   = $this->categories->get_categories( $this->user_id );
        $this->data[ 'activities' ]                 = $this->activities->get_categorie_activities( $this->data[ 'record' ][ 'activity' ][ 'categorie_ID' ] );

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

        $this->_render();

    }



    /*****
     *  restart record
     *  */
    public function restart( $username, $record_id ) {
        $this->_checkUsername( $username );

        $this->data[ 'record' ] = $this->records->get_record_by_id_full( $record_id );
        if ( !$this->data[ 'record' ] )
            show_404();

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
     *  list activities
     *  */

    public function activities( $username ) {
        $this->_checkUsername( $username );
        // TODO!
        $this->data[ 'TODO' ] = "activities page - add running & last activities";
        $this->_render();
    }


    /*****
     *  show activity
     *  */


    public function _generic_activity_show( $username, $activity_id ) {
        $this->data[ 'activity' ] = $this->activities->get_activity_by_id_full( $activity_id );
        if ( !$this->data[ 'activity' ] )
            show_404();
        $this->data[ 'breadcrumb' ] = array(
            array( 'title'=> $this->data[ 'activity' ]['categorie']['title'], 'url'=>'tt'.$username.'/categorie/'.$this->data[ 'activity' ]['categorie_ID']),
            array( 'title'=> $this->data[ 'activity' ]['title'], 'url'=>'')
            );
        $this->data[ 'categories' ]   = $this->categories->get_categories( $this->user_id );
        $this->data[ 'activities' ] = $this->activities->get_categorie_activities( $this->data[ 'activity' ][ 'categorie_ID' ] );
        $this->data[ 'tt_layout' ]  = 'tt_activity';
    }


    public function activity( $username, $activity_id = NULL ) {
        $this->_checkUsername( $username );
        if ( $activity_id == NULL )
            redirect( 'tt/' . $username . '/activities', 'location', 301 );
        $this->_checkRecordType( $activity_id, 'activity' );
        // TODO!
        $this->_generic_activity_show( $username, $activity_id );

        $this->data[ 'TODO' ] = "activity " . $activity_id . " page - add running & last activities";
        $this->_render();
    }


    /*****
     *  show edit activity
     *  */

    public function activity_edit( $username, $activity_id ) {
        $this->_checkUsername( $username );
        $this->_checkRecordType( $activity_id, 'activity' );
        // TODO!
        $this->data[ 'TODO' ] = "activity " . $activity_id . " edit (rename, hide)";
        $this->_render();
    }



    /*****
     *  list todo things
     *  */

    public function thingstodo( $username ) {
        $this->_checkUsername( $username );
        // TODO!
        $this->data[ 'TODO' ] = "things todo page - add running & last activities";
        $this->_render();
    }


    /*****
     *  show todo
     *  */

    public function todo( $username, $activity_id = NULL ) {
        $this->_checkUsername( $username );
        if ( $activity_id == NULL )
            redirect( 'tt/' . $username . '/thingstodo', 'location', 301 );
        $this->_checkRecordType( $activity_id, 'todo' );
        // TODO!
        //$this->data['tt_layout']='tt_activity';
        $this->_generic_activity_show( $username, $activity_id );

        $this->data[ 'TODO' ] = "todo " . $activity_id . " page - add running & last activities";
        $this->_render();
    }


    /*****
     *  show edit todo
     *  */

    public function todo_edit( $username, $activity_id ) {
        $this->_checkUsername( $username );
        $this->_checkRecordType( $activity_id, 'todo' );
        // TODO!
        $this->data[ 'TODO' ] = "todo " . $activity_id . " edit (rename, hide)";
        $this->_render();
    }




    /*****
     *  list values
     *  */

    public function values( $username ) {
        $this->_checkUsername( $username );
        // TODO!
        $this->data[ 'TODO' ] = "values page - add running & last activities";
        $this->_render();
    }


    /*****
     *  show value
     *  */

    public function value( $username, $activity_id = NULL ) {
        $this->_checkUsername( $username );
        if ( $activity_id == NULL )
            redirect( 'tt/' . $username . '/values', 'location', 301 );
        $this->_checkRecordType( $activity_id, 'value' );
        // TODO!
        $this->data[ 'tt_layout' ] = 'tt_activity';
        $this->_generic_activity_show( $username, $activity_id );

        $this->data[ 'TODO' ] = "value " . $activity_id . " page - add running & last activities";
        $this->_render();
    }


    /*****
     *  show edit value
     *  */

    public function value_edit( $username, $activity_id ) {
        $this->_checkUsername( $username );
        $this->_checkRecordType( $activity_id, 'value' );
        // TODO!
        $this->data[ 'TODO' ] = "value " . $activity_id . " edit (rename, hide)";
        $this->_render();
    }





    /*****
     *  list categories
     *  */

    public function categories( $username ) {
        $this->_checkUsername( $username );
        // TODO!
        $this->data[ 'categories' ] = $this->categories->get_categories( $this->user_id );
        $this->data[ 'TODO' ]     = "categories";
        $this->_render();
    }


    /*****
     *  show categorie
     *  */

    public function categorie( $username, $categorie_id = NULL ) {
        $this->_checkUsername( $username );
        if ( $categorie_id == NULL )
            redirect( 'tt/' . $username . '/categories', 'location', 301 );
        // TODO!

        $this->data[ 'categorie' ] = $this->categories->get_categorie_by_id( $categorie_id );
        if ( !$this->data[ 'categorie' ] )
            show_404();
        $this->data[ 'breadcrumb' ] = array(
            array( 'title'=> $this->data[ 'categorie' ]['title'], 'url'=>'')
            );
        $this->data[ 'categories' ]                = $this->categories->get_categories( $this->user_id );
        $this->data[ 'activities' ]                = $this->activities->get_categorie_activities( $categorie_id );
        $this->data[ 'tt_layout' ]                 = 'tt_categorie';

        $this->data[ 'TODO' ] = "categorie " . $categorie_id . " page - add sub activities show shared status total time & activity count list sub cat & running/close activities";
        $this->_render();
    }



    /*****
     *  show categorie edit
     *  */

    public function categorie_edit( $username, $categorie_id ) {
        $this->_checkUsername( $username );
        // TODO!
        $this->data[ 'TODO' ] = "categorie " . $categorie_id . " edit (rename, share, hide)";
        $this->_render();
    }





    /*****
     *  list tags
     *  */

    public function tags( $username ) {
        $this->_checkUsername( $username );
        // TODO!
        $this->data[ 'TODO' ] = "tags page";
        $this->_render();
    }


    /*****
     *  show tag
     *  */

    public function tag( $username, $tag_id = NULL ) {
        $this->_checkUsername( $username );
        if ( $tag_id == NULL )
            redirect( 'tt/' . $username . '/tags', 'location', 301 );
        // TODO!
        $this->data[ 'TODO' ] = "tag " . $tag_id . " page";
        $this->_render();
    }



    /*****
     *  show tag edit
     *  */

    public function tag_edit( $username, $tag_id ) {
        $this->_checkUsername( $username );
        // TODO!
        $this->data[ 'TODO' ] = "tag " . $tag_id . " edit";
        $this->_render();
    }





    /*****
     *  list value_types
     *  */

    public function valuetypes( $username ) {
        $this->_checkUsername( $username );
        // TODO!
        $this->data[ 'TODO' ] = "value types page";
        $this->_render();
    }


    /*****
     *  show value_types
     *  */

    public function valuetype( $username, $valuetype_id = NULL ) {
        $this->_checkUsername( $username );
        if ( $valuetype_id == NULL )
            redirect( 'tt/' . $username . '/valuetypes', 'location', 301 );
        // TODO!
        $this->data[ 'TODO' ] = "valuetype " . $valuetype_id . " page";
        $this->_render();
    }



    /*****
     *  show value_types edit
     *  */

    public function valuetype_edit( $username, $valuetype_id ) {
        $this->_checkUsername( $username );
        // TODO!
        $this->data[ 'TODO' ] = "valuetype " . $valuetype_id . " edit";
        $this->_render();
    }





    /*****
     *  show summary
     *  */

    public function summary( $username, $type_obj = NULL, $id_obj = NULL ) {
        $this->_checkUsername( $username );
        // TODO!
        $this->data[ 'TODO' ] = "summary $type_obj $id_obj";
        $this->_render();
    }


    /*****
     *  show stats
     *  */

    public function stats( $username, $type_obj = NULL, $id_obj = NULL ) {
        $this->_checkUsername( $username );
        // TODO!
        $this->data[ 'TODO' ] = "stats $type_obj $id_obj";
        $this->_render();
    }


    /*****
     *  show export
     *  */

    public function export( $username, $type_obj = NULL, $id_obj = NULL ) {
        $this->_checkUsername( $username );
        // TODO!
        $this->data[ 'TODO' ] = "export $type_obj $id_obj";
        $this->_render();
    }



    /*****
     *  show params
     *  */

    public function params( $username ) {
        $this->_checkUsername( $username );
        // TODO!
        $this->data[ 'TODO' ] = "params";
        $this->_render();
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

        return $res;
    }



    function _start_record( $post ) {
        $this->form_validation->set_rules( 'start', 'Activity', 'trim|required' );

        $res= array();

        if ( $this->form_validation->run() === TRUE ) {

            $param  = array( );
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
            if ( isset( $post[ 'localtime' ] ) )
                $param[ 'diff_greenwich' ] = $post[ 'localtime' ]; // TODO recup greenwich from time

            $res[ 'activity' ] = $this->_create_record( $title, $categorie, $type_record, $param );
            $res[ 'alerts' ]   = array(
                 array(
                     'type' => 'success',
                    'alert' => 'start new activity: ' . $res[ 'activity' ][ 'title' ]
                )
            );

            if ( isset( $tags ) )
                foreach ( $tags as $k => $tag )
                    $this->tags->add_tag_record( $this->user_id, $res[ 'activity' ][ 'record' ][ 'id' ], trim( $tag ) ); // add tags

            if ( element( 'value_name', $post ) )
                $this->values->add_value_record( $this->user_id, $res[ 'activity' ][ 'record' ][ 'id' ], trim( $post[ 'value_name' ] ), trim( $post[ 'value' ] ) ); // add value
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
        $this->form_validation->set_rules( 'update_record', 'Record id', 'required' );
        $this->form_validation->set_rules( 'activity', 'Activity', 'trim|required' );
        $this->form_validation->set_rules( 'start_time', 'Start time', 'required' );
        //$this->form_validation->set_rules( 'start_time', 'Start time', 'required' ); TODO! add dureation check, check start and end date


         $res= array();

        if ( $this->form_validation->run() === TRUE ) {

            $param  = array( );
            $type_record = 'activity';

            if ( $post[ 'activity' ][ 0 ] == '!' )
                $type_record = 'todo';
            if ( $post[ 'activity' ][ 0 ] == '.' )
                $param[ 'running' ] = 0; // ping
            if ( element( 'value_name', $post ) ) {
                $type_record        = 'value';
                $param[ 'running' ] = 0;
            }

            preg_match( '/\[{1}.+\]{1}/i', $post[ 'activity' ], $path_tags ); // get tags from path
            if ( ( $path_tags ) && ( !element( 'tags', $post ) ) )
                $post[ 'tags' ] = trim( $path_tags[ 0 ], '[] ' );

            if ( element( 'tags', $post ) )
                $tags = preg_split( '/,/', $post[ 'tags' ], -1, PREG_SPLIT_NO_EMPTY ); // get tags from input

            $post[ 'activity' ] = preg_replace( '/(\!|\.|\[{1}.+\]{1})*/i', '', $post[ 'activity' ] ); // clean activity path phase1

            if ( $type_record != 'value' ) {
                preg_match( '/\#{1}.+\={1}.+/i', $post[ 'activity' ], $path_value ); // get value from path
                if ( $path_value ) {
                    $path_value_array     = preg_split( '/=/', $path_value[ 0 ], -1, PREG_SPLIT_NO_EMPTY );
                    $post[ 'value_name' ] = trim( $path_value_array[ 0 ], '# ' );
                    $post[ 'value' ]      = trim( $path_value_array[ 1 ] );
                    $type_record          = 'value';
                    $param[ 'running' ]   = 0;
                }
            }

            $post[ 'activity' ] = preg_replace( '/\#{1}.+\={1}.+/i', '', $post[ 'activity' ] ); // clean activity path phase2

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
            if ( isset( $post[ 'localtime' ] ) )
                $param[ 'diff_greenwich' ] = $post[ 'localtime' ]; // TODO recup greenwich from time


            $cat = $this->categories->getorcreate_categorie( $this->user_id, $categorie );

            $res ['activity'] = $this->activities->getorcreate_activity( $cat[ 'id' ], $title, $type_record );

            $update_params=array(
                     'description' => $post[ 'description' ],
                    'start_time' => $post[ 'start_time' ],
                    'activity_ID' => $res ['activity']['id']
                );

            if ( isset( $post[ 'duration' ] ) ) $update_params['duration'] = $post[ 'duration' ];
            if ( isset( $post[ 'running' ] ) ) $update_params['running'] = $post[ 'running' ];
            //if ( isset( $post[ 'diff_greenwich' ] ) ) $update_params['diff_greenwich'] = $post[ 'local_time' ];  TODO! gestion localtime

            $this->records->update_record( $post[ 'update_record' ], $update_params);
            $res[ 'activity' ][ 'record' ] = $this->records->get_record_by_id( $post[ 'update_record' ] );


            $this->tags->reset_record_tags( $post[ 'update_record' ] );
            $this->values->reset_record_values( $post[ 'update_record' ] );

            if ( isset( $tags ) )
                foreach ( $tags as $k => $tag )
                    $this->tags->add_tag_record( $this->user_id, $res[ 'activity' ][ 'record' ][ 'id' ], trim( $tag ) ); // add tags

            if ( element( 'value_name', $post ) )
                $this->values->add_value_record( $this->user_id, $res[ 'activity' ][ 'record' ][ 'id' ], trim( $post[ 'value_name' ] ), trim( $post[ 'value' ] ) ); // add value

            $res[ 'alerts' ]   = array(
                 array(
                     'type' => 'success',
                    'alert' => 'update activity: ' . $res[ 'activity' ][ 'title' ]
                )
            );
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



    /* =================
     * TOOLS
     * ================= */


}

/* End of file test.php */
/* Location: ./application/controllers/timetracker.php */
