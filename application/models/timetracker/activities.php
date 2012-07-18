<?php
if ( !defined( 'BASEPATH' ) )
    exit( 'No direct script access allowed' );

class Activities extends CI_Model {
    private $activities_table = 'activities';
    private $categories_table = 'categories';
    private $records_table = 'records';



    /**
     * Get activity by Id
     *
     * @activity_id     int
     * @return          array
     */
    function get_activity_by_id( $activity_id ) {
        $this->db->where( 'id', $activity_id );

        $query = $this->db->get( $this->activities_table );
        if ( $query->num_rows() >= 1 )
            return $query->row_array();
        return NULL;
    }


    /**
     * Get activity
     *
     * @categorie_id    int
     * @title           string
     * @type_record     string
     * @return          array
     */
    function get_activity( $categorie_id, $title, $type_record ) {
        $this->db->where( 'categorie_ID', $categorie_id );
        $this->db->where( 'title', $title );
        $this->db->where( 'type_of_record', $type_record );

        $query = $this->db->get( $this->activities_table );
        if ( $query->num_rows() >= 1 )
            return $query->row_array();
        return NULL;
    }


    /**
     * Create new activity record
     *
     * @categorie_id    int
     * @title           string
     * @type_record     string
     * @return          array
     */
    function create_activity( $categorie_id, $title, $type_record ) {
        $param = array(
             'title' => strtolower( $title ),
            'categorie_id' => $categorie_id,
            'type_of_record' => $type_record
        );


        if ( $this->db->insert( $this->activities_table, $param ) ) {
            $data = $this->get_activity_by_id( $this->db->insert_id() );
            return $data;
        }
        return NULL;
    }



    /**
     * Get cat activities
     *
     * @categorie_id    int
     * @return          array
     */
    function get_categorie_activities( $categorie_id, $show_empty = FALSE ) {
        $this->db->select( $this->activities_table . '.*' );

        if ( !$show_empty ) {
            $this->db->join( 'records', $this->activities_table . '.id = ' . $this->records_table . '.activity_ID' );
            $this->db->group_by( $this->records_table . '.activity_ID' );
        }

        $this->db->where( 'categorie_ID', $categorie_id );
        $this->db->order_by( 'title' );
        $query = $this->db->get( $this->activities_table );

        if ( $query->num_rows() >= 1 )
            return $query->result_array();
        return NULL;
    }



    /**
     * get or create activity record
     *
     * @categorie_id    int
     * @title           string
     * @param           array
     * @return          array
     */
    function getorcreate_activity( $categorie_id, $title, $type_record ) {
        $res = $this->get_activity( $categorie_id, $title, $type_record );
        if ( !$res )
            $res = $this->create_activity( $categorie_id, $title, $type_record );

        return $res;
    }


    /**
     * Update activity
     *
     * @activity_id     int
     * @title           string
     * @return          boolean
     */
    function update_activity( $activity_id, $param ) {
        $this->db->where( 'id', $activity_id );

        if ( $this->db->update( $this->activities_table, $param ) )
            return TRUE;

        return FALSE;
    }



    function getActivitiespathList( $user_id, $type_of_record ) {
        $this->db->select( $this->activities_table . '.title, ' . $this->categories_table . '.title as categorie_title' );
        $this->db->join( $this->categories_table, $this->activities_table . '.categorie_ID = ' . $this->categories_table . '.id', 'left' );
        $this->db->where( 'user_ID', $user_id );
        $this->db->where( 'type_of_record', $type_of_record );        
        $this->db->order_by( 'title, categorie_title' );
        $query = $this->db->get( $this->activities_table );
        
        $res=array();
        foreach ( $query->result_array() as $activity) {
                if ( $activity[ 'categorie_title' ] != '' )
                    $res[] = $activity[ 'title' ] . '@' . $activity[ 'categorie_title' ];
                else
                    $res[] = $activity[ 'title' ];
        }
        
        return $res;
        
    }


    /* =============
     * TOOLS
     * =============*/


    function get_activity_by_id_full( $activity_id ) {
        $activity = $this->get_activity_by_id( $activity_id );
        if ( $activity )
            $activity = $this->complete_activity_info( $activity );

        return $activity;
    }


    function complete_activity_info( $activity ) {


        $activity[ 'categorie' ] = $this->categories->get_categorie_by_id( $activity['categorie_ID'] );
        $activity[ 'categorie_title' ]= $activity[ 'categorie' ]['title'];


       if ( $activity[ 'categorie_title' ] != '' )
            $activity[ 'activity_path' ] = $activity[ 'title' ] . '@' . $activity[ 'categorie_title' ];
        else
            $activity[ 'activity_path' ] = $activity[ 'title' ];

        return $activity;
    }



} // END Class
