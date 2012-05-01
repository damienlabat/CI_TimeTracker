<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tt_activities extends CI_Model
{
    private $table_name             = 'activities';
    private $categorie_table_name   = 'categories';

    /**
     * Get activity by Id
     *
     * @activity_id     int
     * @return          array
     */
    function get_activity_by_id($activity_id)
    {
        $this->db->where('id', $activity_id);

        $query = $this->db->get($this->table_name);
        if ($query->num_rows() == 1) return $query->row_array();
        return NULL;
    }


    /**
     * Create new activity record
     *
     * @categorie_id    int
     * @title           string
     * @param           array
     * @return          array
     */
    function create_activity( $categorie_id,$title,$param=array() )
    {
        $param = array_merge( $param, array('title' => strtolower($title), 'categorie_id' => $categorie_id) );
        print_r($param);


        if ($this->db->insert($this->table_name, $param)) {
               $data = $this->get_activity_by_id( $this->db->insert_id() );
            return $data;
        }
        return NULL;
    }



} // END Class
