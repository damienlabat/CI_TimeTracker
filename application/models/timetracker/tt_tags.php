<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tt_tags extends CI_Model
{
    private $table_name             = 'tags';
    private $table_link_name        = 'l_activities_tags';



    /**
     * get tag
     *
     * @user_id         int
     * @tag           string
     * @return          array
     */
    function get_tag( $user_id, $tag )
    {
        $this->db->where('tag', $tag);
        $this->db->where('user_ID', $user_id);

        $query = $this->db->get($this->table_name);
        if ($query->num_rows() == 1) return $query->row_array();
        return NULL;
    }



    /**
     * Create new tag record
     *
     * @user_id         int
     * @tag           string
     * @return          array
     */
    function create_tag( $user_id, $tag )
    {
        if ( $this->db->insert($this->table_name, array('tag'=>$tag, 'user_ID'=>$user_id)) ) {
               $data = $this->get_tag( $user_id, $tag );
            return $data;
        }
        return NULL;
    }


    /**
     * Get or Create new tag record
     *
     * @user_id         int
     * @tag           string
     * @return          array
     */
    function getorcreate_tag( $user_id, $tag )
    {
        $res=$this->get_tag($user_id, $tag);
        if (!$res) $res=$this->create_tag($user_id, $tag);

        return $res;
    }


    /**
     * add tag
     *
     * @activity_id     int
     * @tag_id          int
     * @return          boolean
     */
    function add_tag( $activity_id, $tag_id )
    {
        return $this->db->insert($this->table_link_name, array('activity_ID'=>$activity_id, 'tag_ID'=>$tag_id));

    }

    /**
     * remove tag
     *
     * @activity_id     int
     * @tag_id          int
     * @return          boolean
     */
    function remove_tag( $activity_id, $tag_id )
    {
        $res= $this->db->delete($this->table_link_name, array('activity_ID'=>$activity_id, 'tag_ID'=>$tag_id));
        $this->clear_orphan();
        return $res;

    }


     function clear_orphan()
     {
         // TODO !
         // clear table_link where not in tags or not in activities
         // clear tags where not in table_link
     }


} // END Class
