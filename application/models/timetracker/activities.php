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

        if ( $activity[ 'type_of_record' ] == 'todo' )
            $activity[ 'activity_path' ] = '!' . $activity[ 'activity_path' ];

        return $activity;
    }



} // END Class
