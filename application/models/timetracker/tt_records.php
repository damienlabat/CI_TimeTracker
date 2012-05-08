<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tt_records extends CI_Model
{
    private $activities_table   = 'activities';
    private $categories_table   = 'categories';
    private $records_table      = 'records';



    /**
     * Get record by Id
     *
     * @record_id     int
     * @return          array
     */
    function get_record_by_id($record_id)
    {
        $this->db->where('id', $record_id);

        $query = $this->db->get($this->records_table);
        if ($query->num_rows() == 1) return $query->row_array();
        return NULL;
    }



    /**
     * Create new record
     *
     * @activity_id     int
     * @param          string
     */
    function create_record($activity_id,$param)
    {
        $param = array_merge( $param, array('activity_ID' => $activity_id) );


        if ($this->db->insert($this->records_table, $param)) {
               $data = $this->get_record_by_id( $this->db->insert_id() );
            return $data;
        }
        return NULL;
    }



} // END Class
