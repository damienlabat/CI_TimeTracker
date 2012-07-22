<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Timetracker_lib
{


    function __construct()
    {
        $this->ci =& get_instance();

        $this->ci->config->load('timetracker');

        $this->ci->load->helper( array(
            'url',
            'assets_helper',
            'form',
            'timetracker',
            'date',
            'array'
        ) );

        $this->ci->load->library( 'tank_auth' );

        $this->ci->load->model( array(
            'timetracker/categories',
            'timetracker/activities',
            'timetracker/tags',
            'timetracker/values',
            'timetracker/records'
        ) );





        $this->ci->data['ajax'] = $this->ci->input->is_ajax_request();
        $this->ci->data['modal'] = FALSE;
        $this->ci->data['title'] = '';
        $this->ci->data['subtitle'] = '';


    }


    function render( ) {
        $this->getTitleAndBreadcrumb();
        if ( $this->ci->data['ajax'] ) {
                
                if ( (isset($this->ci->data['modal'])) && ($this->ci->data['modal']) ) {
                       $this->ci->data[ 'content' ] = $this->ci->load->view( 'timetracker/layout', $this->ci->data, true ); 
                       $this->ci->load->view( 'modal', $this->ci->data );
                }
                else
                        $this->ci->load->view( 'timetracker/layout', $this->ci->data );
                }
        else {
    
               
                $this->ci->data[ 'content' ] = $this->ci->load->view( 'timetracker/layout', $this->ci->data, true );
                $this->ci->load->view( 'layout', $this->ci->data );

                if ( 0 ) { //DEBUG
                    echo '<pre>';
                     print_r($this->ci->data);
                     echo'</pre>';
                 }
            }
      }




    function checkuser()
    {

        if ( !$this->ci->tank_auth->is_logged_in() ) $this->goLogin();

        $this->ci->user_id   = $this->ci->tank_auth->get_user_id();
        $this->ci->user_name = $this->ci->tank_auth->get_username();
        $this->ci->user_profile = $this->ci->tank_auth->get_profile();

        $this->ci->user_params = $this->getUserParam(); 

        $this->ci->data[ 'user' ][ 'name' ] =   $this->ci->data[ 'current' ][ 'username' ] =       $this->ci->user_name;
        $this->ci->data[ 'user' ][ 'id' ]   =        $this->ci->user_id;
        $this->ci->data[ 'user' ][ 'params' ]   =    $this->ci->user_params;


        $this->ci->db->query( "SET time_zone= '". timezone2UTCdiff( $this->ci->user_params['timezone'] ) ."'" );


        $query = $this->ci->db->query( "SELECT NOW() as now" );
        $this->ci->server_time   =$query->row()->now;
        $this->ci->data[ 'server_time' ]=$this->ci->server_time;

        $this->ci->server_date   = preg_split('/ /', $this->ci->server_time);
        $this->ci->data[ 'server_date' ]=  $this->ci->server_date= $this->ci->server_date[0];

        $this->ci->firstdata_date   = $this->ci->records->get_min_time($this->ci->user_id);
        $this->ci->data[ 'firstdata_date' ]=  $this->ci->firstdata_date;

         /** get data **/


         // GET VAR

        $this->ci->data[ 'current' ]['page']= element('page', $_GET, '1');

        $this->ci->data[ 'current' ]['tab']= element('tab', $_GET, 'activity');

        $this->ci->data[ 'current' ]['datefrom']= element('datefrom', $_GET, $this->ci->data[ 'firstdata_date' ]);
        $this->ci->data[ 'current' ]['dateto']= element('dateto', $_GET, $this->ci->data['server_date']);
        $this->ci->data[ 'current' ]['categorie']= element('categorie', $_GET, NULL);
        $this->ci->data[ 'current' ]['tag']= element('tag', $_GET, NULL);
        $this->ci->data[ 'current' ]['groupby']= element('groupby', $_GET, 'day');
        $this->ci->data[ 'current' ]['graphid']= element('graphid', $_GET, NULL);


         $this->getRunnings();
    }



    function getUserParam() {
        $def_val= array(
                'timezone'      =>      'Europe/Paris',
                'language'      =>      'en',
                'startactivitymode'  => 'by_categorie'
        );
        $json_param= json_decode( $this->ci->user_profile['params'], true );
        foreach ($def_val as $k=>$v)
                if ( !isset( $json_param[$k] ) ) 
                        $json_param[$k] = $v;
        return $json_param;
    }



    function getCurrentObj(){
        $current=$this->ci->data['current'];

        if ( $current['id'] !=NULL ) {


            if ( in_array( $current['cat'], array('activity','todo','value') ) )
                $this->ci->data[ 'activity' ]       = $res = $this->ci->activities->get_activity_by_id_full( $current['id'] );

            if ($current['cat']=='categorie')
                $this->ci->data[ 'categorie' ]      = $res = $this->ci->categories->get_categorie_by_id( $current['id'] );

            if ($current['cat']=='record')
                $this->ci->data[ 'record' ]         = $res = $this->ci->records->get_record_by_id_full( $current['id'] );

            if ($current['cat']=='tag')
                 $this->ci->data[ 'tag' ]           = $res = $this->ci->tags->get_tag_by_id($current['id'] );


            if ( !$res )  show_404();

        }
    }
    
    
    function getTitleAndBreadcrumb(){    
        $current=$this->ci->data['current'];
        $username=$current['username'];  
    
        $title = $subtitle = '';
        $breadcrumb= array();
        $breadcrumb[]= array( 'title'=> 'home', 'url'=>site_url('tt/'.$username) );       
        
        
            
        
        if ($current['cat']=='record') {
                $record=$this->ci->data['record'];
                
                switch ($record['activity']['type_of_record']) {
                    case 'activity':
                        $breadcrumb[]=  array( 'title'=> 'activities', 'url'=>site_url('tt/'.$username.'/activities') ); 
                        break;
                    case 'todo':
                        $breadcrumb[]=  array( 'title'=> 'todo list', 'url'=>site_url('tt/'.$username.'/todolist') ); 
                        break;
                    case 'value':
                        $breadcrumb[]=  array( 'title'=> 'values', 'url'=>site_url('tt/'.$username.'/values') );  
                        break;
                }
               $breadcrumb[]=  array( 'title'=> format_categorie($record[ 'activity' ]['categorie']),   'url'=>tt_url($username,'record',$current, array('cat'=>'categorie', 'id'=>$record[ 'activity' ]['categorie_ID'])) );
               $breadcrumb[]=  array( 'title'=> $record[ 'activity' ]['title'],                'url'=>tt_url($username,'record',$current, array('cat'=>$record[ 'activity' ]['type_of_record'],'id'=>$record[ 'activity' ]['id'])) );
               $breadcrumb[]=  array( 'title'=> 'start at : '.$record[ 'start_time' ],         'url'=>tt_url($username,'record',$current ));
               $title=$record[ 'activity' ]['title']. ' record start at '.$record[ 'start_time' ];

        }
        
        if ($current['cat']=='activity') {
                $activity=$this->ci->data['activity'];
                
                switch ($activity['type_of_record']) {
                    case 'activity':
                        $breadcrumb[]=  array( 'title'=> 'activities', 'url'=>site_url('tt/'.$username.'/activities') ); 
                        break;
                    case 'todo':
                        $breadcrumb[]=  array( 'title'=> 'todo list', 'url'=>site_url('tt/'.$username.'/todolist') ); 
                        break;
                    case 'value':
                        $breadcrumb[]=  array( 'title'=> 'values', 'url'=>site_url('tt/'.$username.'/values') );  
                        break;
                }
               $breadcrumb[]=  array( 'title'=> format_categorie($activity['categorie']),   'url'=>tt_url($username,'record',$current, array('cat'=>'categorie', 'id'=>$activity['categorie_ID'])) );
               $breadcrumb[]=  array( 'title'=> $activity['title'],                'url'=>tt_url($username,'record',$current) );   
               $title=$activity['title'];           
        }
        
        if (($current['cat']=='categorie')&&($current['id']!=NULL)) {
               $category=$this->ci->data['categorie'];
               $breadcrumb[]=  array( 'title'=> format_categorie($category),   'url'=>tt_url($username,'record',$current) );
               $title=format_categorie($category);
       
        }
        
        if ($current['cat']=='tag') {
               $breadcrumb[]=  array( 'title'=> 'tag: '.$this->ci->data[ 'tag' ]['tag'], 'url'=>'');
               $title= 'tag: '.$this->ci->data[ 'tag' ]['tag'];
        }
        
        if ($current['page']=='activities') $breadcrumb[]=  array( 'title'=> 'activities', 'url'=>site_url('tt/'.$username.'/activities') ); 
        if ($current['page']=='todolist') $breadcrumb[]=  array( 'title'=> 'todo list', 'url'=>site_url('tt/'.$username.'/todolist') ); 
        if ($current['page']=='values') $breadcrumb[]=  array( 'title'=> 'values', 'url'=>site_url('tt/'.$username.'/values') ); 
        
        if ($current['page']=='settings') {
                $breadcrumb[]=  array( 'title'=> 'settings',    'url'=>site_url('tt/'.$username.'/settings') );
                $title= 'settings';
                }
               
                
        if (isset($current['subtitle'])) $subtitle=$current['subtitle'];
    
        $this->ci->data[ 'breadcrumb' ] = $breadcrumb;
        $this->ci->data[ 'title' ] = $title;
        $this->ci->data[ 'subtitle' ] = $subtitle;
    }


    function getRunnings(){
        $this->ci->data[ 'running' ][ 'activities' ]    = $this->ci->records->get_records_full($this->ci->user_id, array( 'type_of_record' => 'activity',  'running' => TRUE ) );
        $this->ci->data[ 'running' ][ 'todos' ]         = $this->ci->records->get_records_full($this->ci->user_id, array( 'type_of_record' => 'todo',      'running' => TRUE ) );

    }



    function goLogin( ) {
        redirect( 'login', 'location', 301 );
    }


    function checkUsername( $username ) {
        if ( $username != $this->ci->user_name )
            $this->goLogin(); //TODO shared folder gestion
    }


    function get_alerts(){
        $this->ci->data[ 'alerts' ] = array( );
        if ( $this->ci->session->flashdata( 'alerts' ) )
            $this->ci->data[ 'alerts' ] = $this->ci->session->flashdata( 'alerts' ); //array( array('type'=>'success', 'alert'=>'error 1 .....') );

    }

}

/* End of file Tank_auth.php */
/* Location: ./application/libraries/Tank_auth.php */
