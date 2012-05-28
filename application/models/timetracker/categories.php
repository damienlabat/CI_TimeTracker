<?php
if ( !defined( 'BASEPATH' ) )
    exit( 'No direct script access allowed' );

class Categories extends CI_Model {
    private $categories_table = 'categories';
    private $activities_table = 'activities';

    /**
     * Get categorie by Id
     *
     * @categorie_id    int
     * @return          array
     */
    function get_categorie_by_id( $categorie_id ) {
        $this->db->where( 'id', $categorie_id );

        $query = $this->db->get( $this->categories_table );
        if ( $query->num_rows() >= 1 )
            return $query->row_array();
        return NULL;
    }


    /**
     * Get categorie by Title
     *
     * @user_id         int
     * @title           string
     * @return          array
     */
    function get_categorie_by_title( $user_id, $title ) {
        $this->db->where( 'LOWER(title)', strtolower( $title ) );
        $this->db->where( 'user_ID', $user_id );

        $query = $this->db->get( $this->categories_table );
        if ( $query->num_rows() >= 1 )
            return $query->row_array();
        return NULL;
    }


    /**
     * Create new categorie record
     *
     * @user_id         int
     * @title           string
     * @return          array
     */
    function create_categorie( $user_id, $title ) {
        $data = array(
             'title' => strtolower( $title ),
            'user_ID' => $user_id
        );

        if ( $this->db->insert( $this->categories_table, $data ) ) {
            $data = $this->get_categorie_by_id( $this->db->insert_id() );
            return $data;
        }
        return NULL;
    }


    /**
     * Update  categorie record
     *
     * @id         int
     * @param            array
     */
    function update_categorie( $id, $param ) {
        $this->db->where( 'id', $id );

        if ( $this->db->update( $this->categories_table, $param ) )
            return TRUE;

        return FALSE;
    }



    /**
     * Get or Create new categorie record
     *
     * @user_id         int
     * @title           string
     * @return          array
     */
    function getorcreate_categorie( $user_id, $title ) {
        $res = $this->get_categorie_by_title( $user_id, $title );
        if ( !$res )
            $res = $this->create_categorie( $user_id, $title );

        return $res;
    }


    /**
     * Get categories
     *
     * @user_id         int
     * @title           string
     * @return          array
     */
    function get_categories( $user_id ) {
        $this->db->select( $this->categories_table . '.*, count( '.$this->activities_table.'.id ) as nb_activities' );
        $this->db->where( 'user_ID', $user_id );
        $this->db->join( $this->activities_table, $this->activities_table.'.categorie_ID = '.$this->categories_table.'.id', 'left');
        $this->db->group_by( $this->categories_table . '.id' );
        $this->db->order_by( 'title' );

        $query = $this->db->get( $this->categories_table );
        if ( $query->num_rows() >= 1 )
            return $query->result_array();
        return NULL;
    }



    // TODO! shared categorie gestion





} // END Class
