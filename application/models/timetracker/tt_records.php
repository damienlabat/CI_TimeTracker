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
        //$this->db->where('id', $record_id);

       // $query = $this->db->get($this->records_table);


        $this->db->select($this->activities_table.'.title,type_of_record,categorie_ID,'.$this->records_table.'.*');
        $this->db->from($this->records_table);
        $this->db->join($this->activities_table, $this->activities_table.'.id = '. $this->records_table.'.activity_ID');
        $this->db->where($this->records_table.'.id',$record_id);

        $query = $this->db->get();

        if ($query->num_rows() == 1) return $query->row_array();
        return NULL;
    }



    /**
     * Create new record
     *
     * @activity_id     int
     * @param           string
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



    /**
     * Get running activity records
     *
     * @user_id     int
     * @return      array
     */
    function get_running_activities($user_id)
    {
        $query =  $this->db->query(
            'SELECT '.$this->activities_table.'.title,type_of_record,categorie_ID,'.$this->records_table.'.*
             FROM '.$this->records_table.'
             LEFT JOIN '.$this->activities_table.'
                ON '.$this->records_table.'.activity_ID='.$this->activities_table.'.id
             LEFT JOIN '.$this->categories_table.'
                ON '.$this->activities_table.'.categorie_ID='.$this->categories_table.'.id
            WHERE
                user_ID='.$user_id.'
                AND running=1
                AND type_of_record=\'activity\'
            ORDER BY start_time DESC'
            );

        if ($query->num_rows() >= 1) return $query->result_array();
        return NULL;
    }


    /**
     * Get running todo records
     *
     * @user_id     int
     * @return          array
     */
    function get_running_TODO($user_id)
    {
        $query =  $this->db->query(
            'SELECT '.$this->activities_table.'.title,type_of_record,categorie_ID,'.$this->records_table.'.*
             FROM '.$this->records_table.'
             LEFT JOIN '.$this->activities_table.'
                ON '.$this->records_table.'.activity_ID='.$this->activities_table.'.id
             LEFT JOIN '.$this->categories_table.'
                ON '.$this->activities_table.'.categorie_ID='.$this->categories_table.'.id
            WHERE
                user_ID='.$user_id.'
                AND running=1
                AND type_of_record=\'todo\'
            ORDER BY start_time DESC'
            );

        if ($query->num_rows() >= 1) return $query->result_array();
        return NULL;
    }




    /**
     * Get last records
     *
     * @user_id     int
     * @offset          int
     * @count           int
     * @return          array
     */
    function get_last_activities($user_id,$offset,$count)
    {
        $query =  $this->db->query(
            'SELECT '.$this->activities_table.'.title,type_of_record,categorie_ID,'.$this->records_table.'.*
             FROM '.$this->records_table.'
             LEFT JOIN '.$this->activities_table.'
                ON '.$this->records_table.'.activity_ID='.$this->activities_table.'.id
             LEFT JOIN '.$this->categories_table.'
                ON '.$this->activities_table.'.categorie_ID='.$this->categories_table.'.id
            WHERE
                user_ID='.$user_id.'
                AND running=0

            ORDER BY UNIX_TIMESTAMP(start_time)+duration DESC
            LIMIT '.$offset.','.$count
            );

        if ($query->num_rows() >= 1) return $query->result_array();
        return NULL;
    }


     /**
     * Update record
     *
     * @record_id       int
     * @title           string
     * @return          boolean
     */
    function update_record( $record_id, $param )
    {
        $this->db->where('id', $record_id);

        if ($this->db->update($this->records_table, $param)) return TRUE;

        return FALSE;
    }


} // END Class
