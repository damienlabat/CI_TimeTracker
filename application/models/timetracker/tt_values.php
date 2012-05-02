<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tt_values extends CI_Model
{
    private $table_name             = 'values_type';
    private $table_link_name        = 'values';



    /**
     * get value
     *
     * @value_type_id   int
     * @activity_id     int
     * @return          array
     */
    function get_value( $activity_id,$value_type_id )
    {
        $this->db->where('value_type_ID', $value_type_id);
        $this->db->where('activity_ID', $activity_id);

        $query = $this->db->get($this->table_link_name);
        if ($query->num_rows() == 1) return $query->row_array();
        return NULL;
    }

    /**
     * get value_type
     *
     * @value_type_id   int
     * @return          array
     */
    function get_value_type( $value_type_id )
    {
        $this->db->where('id', $value_type_id);

        $query = $this->db->get($this->table_name);
        if ($query->num_rows() == 1) return $query->row_array();
        return NULL;
    }


    /**
     * get value_type_by_name
     *
     * @user_id     int
     * @title       sting
     * @return      array
     */
    function value_type_by_title( $user_id,$title )
    {
        $this->db->where('user_ID', $user_id);
        $this->db->where('title', $title);

        $query = $this->db->get($this->table_name);
        if ($query->num_rows() == 1) return $query->row_array();
        return NULL;
    }




    /**
     * Create new value_type record
     *
     * @user_id         int
     * @title           string
     * @return          array
     */
    function create_value_type( $user_id, $title )
    {
        if ( $this->db->insert($this->table_name, array('title'=>$title, 'user_ID'=>$user_id)) ) {
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
    function getorcreate_value_type( $user_id, $title )
    {
        $res=$this->value_type_by_title($user_id, $title);
        if (!$res) $res=$this->create_value_type($user_id, $title);

        return $res;
    }


    /**
     * add value
     *
     * @activity_id     int
     * @value_type_id   int
     * @value           string
     * @return          boolean
     */
    function add_value( $activity_id, $value_type_id,$value )
    {
        return $this->db->insert($this->table_link_name, array('activity_ID'=>$activity_id, 'value_type_ID'=>$value_type_id, 'value'=>$value));

    }

    /**
     * remove value
     *
     * @activity_id     int
     * @tag_id          int
     * @return          boolean
     */
    function remove_value( $activity_id, $value_type_id )
    {
        $res= $this->db->delete($this->table_link_name, array('activity_ID'=>$activity_id, 'value_type_ID'=>$value_type_id));
        $this->clear_orphan();
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
    function update_value_type( $user_id, $title, $param )
    {
        $this->db->where('user_ID', $user_id);
        $this->db->where('title', $title);
        if ($this->db->update($this->table_name, $param ))
            return TRUE;

        return FALSE;
    }


    /**
     * Update value
     *
     * @activity_id     int
     * @value_type_id   int
     * @value           string
     * @return          array
     */
    function update_value( $activity_id, $value_type_id, $value )
    {
        $this->db->where('activity_ID', $activity_id);
        $this->db->where('value_type_ID', $value_type_id);
        if ($this->db->update($this->table_name, array('value'=>$value) ))
            return TRUE;

        return FALSE;
    }

     function clear_orphan()
     {
         // TODO !
         // clear table_link where not in tags or not in activities
         // clear tags where not in table_link SHOULD WE ???
     }


} // END Class
