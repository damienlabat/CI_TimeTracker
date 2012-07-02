<?php
if ( !defined( 'BASEPATH' ) )
    exit( 'No direct script access allowed' );

class Values extends CI_Model {
    private $values_table = 'values';



    /**
     * get value
     *
     * @record_ID     int
     * @return        string
     */
    function get_value( $record_id ) {

        $this->db->where( 'record_ID', $record_id );

        $query = $this->db->get( $this->values_table );
        if ( $query->num_rows() == 1 ) {
            if (is_numeric($query->row()->value))
                return array($query->row()->value);
            else
                return json_decode( $query->row()->value );
        }
        return NULL;
    }



    /**
     * add value
     *
     * @record_id       int
     * @value           string
     * @return          boolean
     */
    function add_value( $record_id, $value ) {
        return $this->db->insert( $this->values_table, array(
            'record_ID' => $record_id,
            'value' => $value
        ) );

    }

    /**
     * remove value
     *
     * @record_id       int
     * @return          boolean
     */
    function remove_value( $record_id ) {
        $res = $this->db->delete( $this->values_table, array(
            'record_ID' => $record_id,
        ) );
        return $res;
    }



    /**
     * Update value
     *
     * @record_id       int
     * @value           string
     * @return          boolean
     */
    function update_value( $record_id, $value ) {
        $this->db->where( 'record_ID', $record_id );
        if ( $this->db->update( $this->values_table, array(
             'value' => $value
        ) ) )
            return TRUE;

        return FALSE;
    }


} // END Class
