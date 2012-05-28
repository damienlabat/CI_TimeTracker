<?php
if ( !defined( 'BASEPATH' ) )
    exit( 'No direct script access allowed' );

class Values extends CI_Model {
    private $values_type_table = 'valuetypes';
    private $l_record_values_table = 'l_records_values';



    /**
     * get value
     *
     * @valuetype_id   int
     * @record_ID     int
     * @return          array
     */
    function get_value( $record_id, $valuetype_id ) {
        $this->db->where( 'valuetype_ID', $valuetype_id );
        $this->db->where( 'record_ID', $record_id );

        $query = $this->db->get( $this->l_record_values_table );
        if ( $query->num_rows() >= 1 )
            return $query->row_array();
        return NULL;
    }



     /**
     * get valuetype
     *
     * @user_id         int
     * @title           string
     * @return          array
     */
    function get_valuetype( $user_id, $title ) {
        $this->db->where( 'user_ID', $user_id );
        $this->db->where( 'title', $title );

        $query = $this->db->get( $this->values_type_table );
        if ( $query->num_rows() >= 1 )
            return $query->row_array();
        return NULL;
    }


    /**
     * get valuetype_by_id
     *
     * @valuetype_id   int
     * @return          array
     */
    function get_valuetype_by_id( $valuetype_id ) {
        $this->db->where( 'id', $valuetype_id );

        $query = $this->db->get( $this->values_type_table );
        if ( $query->num_rows() >= 1 )
            return $query->row_array();
        return NULL;
    }



    /**
     * get valuetype list
     *
     * @user_id         int
     * @return          array
     */
    function get_valuetype_list( $user_id ) {
        $query = $this->db->query( 'SELECT values_types.* , count( record_ID ) AS count
            FROM values_types
                LEFT JOIN l_activities_values ON l_activities_values.valuetype_ID  = values_types.id
            WHERE user_ID="' . $user_id . '"
            GROUP BY id
            ORDER BY title' );

        if ( $query->num_rows() >= 1 )
            return $query->result_array();
        return NULL;
    }


    /**
     * get valuetype_by_name
     *
     * @user_id     int
     * @title       sting
     * @return      array
     */
    function valuetype_by_title( $user_id, $title ) {
        $this->db->where( 'user_ID', $user_id );
        $this->db->where( 'title', $title );

        $query = $this->db->get( $this->values_type_table );
        if ( $query->num_rows() >= 1 )
            return $query->row_array();
        return NULL;
    }




    /**
     * Create new valuetype record
     *
     * @user_id         int
     * @title           string
     * @return          array
     */
    function create_valuetype( $user_id, $title ) {
        if ( $this->db->insert( $this->values_type_table, array(
             'title' => $title,
            'user_ID' => $user_id
        ) ) ) {
            $data = $this->valuetype_by_title( $user_id, $title );
            return $data;
        }
        return NULL;
    }


    /**
     * Get or Create new valuetype record
     *
     * @user_id         int
     * @title           string
     * @return          array
     */
    function getorcreate_valuetype( $user_id, $title ) {
        $res = $this->valuetype_by_title( $user_id, $title );
        if ( !$res )
            $res = $this->create_valuetype( $user_id, $title );

        return $res;
    }


    /**
     * add value
     *
     * @record_id       int
     * @valuetype_id   int
     * @value           string
     * @return          boolean
     */
    function add_value( $record_id, $valuetype_id, $value ) {
        return $this->db->insert( $this->l_record_values_table, array(
             'record_ID' => $record_id,
            'valuetype_ID' => $valuetype_id,
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
    function remove_value( $record_id, $valuetype_id ) {
        $res = $this->db->delete( $this->l_record_values_table, array(
             'record_ID' => $record_id,
            'valuetype_ID' => $valuetype_id
        ) );
        return $res;
    }

    /**
     * Update valuetype
     *
     * @id              int
     * @param           array
     * @return          boolean
     */
    function update_valuetype( $id, $param ) {
        $this->db->where( 'id', $id );
        if ( $this->db->update( $this->values_type_table, $param ) )
            return TRUE;

        return FALSE;
    }


    /**
     * Update value
     *
     * @record_id     int
     * @valuetype_id   int
     * @value           string
     * @return          array
     */
    function update_value( $record_id, $valuetype_id, $value ) {
        $this->db->where( 'record_ID', $record_id );
        $this->db->where( 'valuetype_ID', $valuetype_id );
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
        $this->db->join( $this->l_record_values_table, $this->values_type_table . '.id = ' . $this->l_record_values_table . '.valuetype_ID' );
        $this->db->where( 'record_ID', $record_id );

        $query = $this->db->get();
        if ( $query->num_rows() >= 1 )
            return $query->row_array();
        return NULL;

    }




    /* =============
     * TOOLS
     * =============*/

    function add_value_record( $user_id, $record_id, $value_name, $value ) {
        $value_obj = $this->getorcreate_valuetype( $user_id, $value_name );
        if ( $this->add_value( $record_id, $value_obj[ 'id' ], $value ) )
            return $this->get_value( $record_id, $value_obj[ 'id' ] );
        return NULL;
    }


    function remove_value_record( $user_id, $record_id, $value_name ) {
        $valuetype_obj = $this->valuetype_by_title( $user_id, $value_name );
        if ( !$valuetype_obj )
            return FALSE;
        return $this->remove_value( $record_id, $valuetype_obj[ 'id' ] );

    }


    function reset_record_values( $record_id ) {
       $res = $this->db->delete( $this->l_record_values_table, array(
             'record_ID' => $record_id
       ) );
       return $res;
    }

} // END Class
