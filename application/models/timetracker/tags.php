<?php
if ( !defined( 'BASEPATH' ) )
    exit( 'No direct script access allowed' );

class Tags extends CI_Model {
    private $tags_table = 'tags';
    private $l_records_tags_table = 'l_records_tags';

    /**
     * get tag by id
     *
     * @id         int
     * @return          array
     */
    function get_tag_by_id( $id ) {
        $this->db->where( 'id', $id );

        $query = $this->db->get( $this->tags_table );
        if ( $query->num_rows() >= 1 )
            return $query->row_array();
        return NULL;
    }


    /**
     * get tag
     *
     * @user_id         int
     * @tag           string
     * @return          array
     */
    function get_tag( $user_id, $tag ) {
        $this->db->where( 'tag', $tag );
        $this->db->where( 'user_ID', $user_id );

        $query = $this->db->get( $this->tags_table );
        if ( $query->num_rows() >= 1 )
            return $query->row_array();
        return NULL;
    }


    /**
     * get tag list
     *
     * @user_id         int
     * @return          array
     */
    function get_tag_list( $user_id ) {
        $query = $this->db->query( //TODO
            'SELECT tags . * , count( activity_id ) AS count
            FROM tags
                LEFT JOIN l_activities_tags ON tags.id = l_activities_tags.tag_ID
            WHERE user_ID="' . $user_id . '"
            GROUP BY id
            ORDER BY tag' );

        if ( $query->num_rows() >= 1 )
            return $query->result_array();
        return NULL;
    }


    /**
     * Create new tag record
     *
     * @user_id         int
     * @tag             string
     * @return          array
     */
    function create_tag( $user_id, $tag ) {
        $tag = url_title( $tag );
        if ( $this->db->insert( $this->tags_table, array(
             'tag' => $tag,
            'user_ID' => $user_id
        ) ) ) {
            $data = $this->get_tag( $user_id, $tag );
            return $data;
        }
        return NULL;
    }


    /**
     * Update tag
     *
     * @user_id         int
     * @tag             string
     * @param           array
     * @return          boolean
     */
    function update_tag( $user_id, $tag, $param ) {
        $this->db->where( 'user_ID', $user_id );
        $this->db->where( 'tag', $tag );
        if ( $this->db->update( $this->tags_table, $param ) )
            return TRUE;

        return FALSE;
    }

    /**
     * Get or Create new tag record
     *
     * @user_id         int
     * @tag           string
     * @return          array
     */
    function getorcreate_tag( $user_id, $tag ) {
        $res = $this->get_tag( $user_id, $tag );
        if ( !$res )
            $res = $this->create_tag( $user_id, $tag );

        return $res;
    }


    /**
     * add tag
     *
     * @activity_id     int
     * @tag_id          int
     * @return          boolean
     */
    function add_tag( $record_id, $tag_id ) {
        return $this->db->insert( $this->l_records_tags_table, array(
             'record_ID' => $record_id,
            'tag_ID' => $tag_id
        ) );

    }

    /**
     * remove tag
     *
     * @activity_id     int
     * @tag_id          int
     * @return          boolean
     */
    function remove_tag( $record_id, $tag_id ) {
        $res = $this->db->delete( $this->l_records_tags_table, array(
             'record_ID' => $record_id,
            'tag_ID' => $tag_id
        ) );
        return $res;

    }



    /**
     * get record tags
     *
     * @record_id     int
     * @return          array
     */
    function get_record_tags( $record_id ) {
        $this->db->select( '*' );
        $this->db->from( $this->tags_table );
        $this->db->join( $this->l_records_tags_table, $this->tags_table . '.id = ' . $this->l_records_tags_table . '.tag_ID' );
        $this->db->where( 'record_ID', $record_id );
        $this->db->order_by( 'tag', 'asc' );

        $query = $this->db->get();
        if ( $query->num_rows() >= 1 )
            return $query->result_array();
        return NULL;

    }



    /* =============
     * TOOLS
     * =============*/

    function add_tag_record( $user_id, $record_id, $tag ) {
        $tag_obj = $this->getorcreate_tag( $user_id, $tag );
        if ( $this->add_tag( $record_id, $tag_obj[ 'id' ] ) )
            return $tag_obj;
        return NULL;
    }

    function remove_tag_record( $user_id, $record_id, $tag ) {
        $tag_obj = $this->get_tag( $user_id, $tag );
        if ( !$tag_obj )
            return FALSE;
        return $this->remove_tag( $record_id, $tag_obj[ 'id' ] );

    }


    function reset_record_tags( $record_id ) {
       $res = $this->db->delete( $this->l_records_tags_table, array(
             'record_ID' => $record_id
       ) );
       return $res;
    }




} // END Class
