<?php
if ( !defined( 'BASEPATH' ) )
    exit( 'No direct script access allowed' );

class Values extends CI_Model {
    private $values_type_table = 'values_types';
    private $l_record_values_table = 'l_records_values';



    /**
     * get value
     *
     * @value_type_id   int
     * @record_ID     int
     * @return          array
     */
    function get_value( $record_id, $value_type_id ) {
        $this->db->where( 'value_type_ID', $value_type_id );
        $this->db->where( 'record_ID', $record_id );

        $query = $this->db->get( $this->l_record_values_table );
        if ( $query->num_rows() == 1 )
            return $query->row_array();
        return NULL;
    }

    /**
     * get value_type_by_id
     *
     * @value_type_id   int
     * @return          array
     */
    function get_value_type_by_id( $value_type_id ) {
        $this->db->where( 'id', $value_type_id );

        $query = $this->db->get( $this->values_type_table );
        if ( $query->num_rows() == 1 )
            return $query->row_array();
        return NULL;
    }



    /**
     * get value_type list
     *
     * @user_id         int
     * @return          array
     */
    function get_value_type_list( $user_id ) {
        $query = $this->db->query( 'SELECT values_types.* , count( record_ID ) AS count
            FROM values_types
                LEFT JOIN l_activities_values ON l_activities_values.value_type_ID  = values_types.id
            WHERE user_ID="' . $user_id . '"
            GROUP BY id
            ORDER BY title' );

        if ( $query->num_rows() >= 1 )
            return $query->result_array();
        return NULL;
    }


    /**
     * get value_type_by_name
     *
     * @user_id     int
     * @title       sting
     * @return      array
     */
    function value_type_by_title( $user_id, $title ) {
        $this->db->where( 'user_ID', $user_id );
        $this->db->where( 'title', $title );

        $query = $this->db->get( $this->values_type_table );
        if ( $query->num_rows() == 1 )
            return $query->row_array();
        return NULL;
    }




    /**
     * Create new value_type record
     *
     * @user_id         int
     * @title           string
     * @return          array
     */
    function create_value_type( $user_id, $title ) {
        if ( $this->db->insert( $this->values_type_table, array(
             'title' => $title,
            'user_ID' => $user_id
        ) ) ) {
            $data = $this->value_type_by_title( $user_id, $title );
            return $data;
        }
        return NULL;
    }


    /**
     * Get or Create new value_type record
     *
     * @user_id         int
     * @title           string
     * @return          array
     */
    function getorcreate_value_type( $user_id, $title ) {
        $res = $this->value_type_by_title( $user_id, $title );
        if ( !$res )
            $res = $this->create_value_type( $user_id, $title );

        return $res;
    }


    /**
     * add value
     *
     * @record_id       int
     * @value_type_id   int
     * @value           string
     * @return          boolean
     */
    function add_value( $record_id, $value_type_id, $value ) {
        return $this->db->insert( $this->l_record_values_table, array(
             'record_ID' => $record_id,
            'value_type_ID' => $value_type_id,
            'value' => $value
        ) );

    }

    /**
     * remove value
     *
     * @record_id       int
     * @tag_id          int
     * @return          boolean
     */
    function remove_value( $record_id, $value_type_id ) {
        $res = $this->db->delete( $this->l_record_values_table, array(
             'record_ID' => $record_id,
            'value_type_ID' => $value_type_id
        ) );
        return $res;
    }

    /**
     * Update value_type
     *
     * @user_id         int
     * @title           string
     * @param           array
     * @return          boolean
     */
    function update_value_type( $user_id, $title, $param ) {
        $this->db->where( 'user_ID', $user_id );
        $this->db->where( 'title', $title );
        if ( $this->db->update( $this->values_type_table, $param ) )
            return TRUE;

        return FALSE;
    }


    /**
     * Update value
     *
     * @record_id     int
     * @value_type_id   int
     * @value           string
     * @return          array
     */
    function update_value( $record_id, $value_type_id, $value ) {
        $this->db->where( 'record_ID', $record_id );
        $this->db->where( 'value_type_ID', $value_type_id );
        if ( $this->db->update( $this->values_type_table, array(
             'value' => $value
        ) ) )
            return TRUE;

        return FALSE;
    }




    /**
     * get record value
     *
     * @record_id     int
     * @return          array
     */
    function get_record_value( $record_id ) {
        $this->db->select( $this->values_type_table . '.*, ' . $this->l_record_values_table . '.*' );
        $this->db->from( $this->values_type_table );
        $this->db->join( $this->l_record_values_table, $this->values_type_table . '.id = ' . $this->l_record_values_table . '.value_type_ID' );
        $this->db->where( 'record_ID', $record_id );

        $query = $this->db->get();
        if ( $query->num_rows() == 1 )
            return $query->row_array();
        return NULL;

    }




    /* =============
     * TOOLS
     * =============*/

    function add_value_record( $user_id, $record_id, $value_name, $value ) {
        $value_obj = $this->getorcreate_value_type( $user_id, $value_name );
        if ( $this->add_value( $record_id, $value_obj[ 'id' ], $value ) )
            return $this->get_value( $record_id, $value_obj[ 'id' ] );
        return NULL;
    }


    function remove_value_record( $user_id, $record_id, $value_name ) {
        $value_type_obj = $this->value_type_by_title( $user_id, $value_name );
        if ( !$value_type_obj )
            return FALSE;
        return $this->remove_value( $record_id, $value_type_obj[ 'id' ] );

    }


    function reset_record_values( $record_id ) {
       $res = $this->db->delete( $this->l_record_values_table, array(
             'record_ID' => $record_id
       ) );
       return $res;
    }

} // END Class
