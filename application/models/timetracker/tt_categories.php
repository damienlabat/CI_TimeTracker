<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tt_categories extends CI_Model
{
    private $table_name         = 'categories';

    /**
     * Get categorie by Id
     *
     * @categorie_id    int
     * @return          array
     */
    function get_categorie_by_id($categorie_id)
    {
        $this->db->where('id', $categorie_id);

        $query = $this->db->get($this->table_name);
        if ($query->num_rows() == 1) return $query->row_array();
        return NULL;
    }

    /**
     * Get categorie by Title
     *
     * @user_id         int
     * @title           string
     * @parent          int
     * @return          array
     */
    function get_categorie_by_title($user_id, $title,$parent)
    {
        $this->db->where('LOWER(title)', strtolower($title));
        $this->db->where('user_ID', $user_id);
        $this->db->where('parent', $parent);

        $query = $this->db->get($this->table_name);
        if ($query->num_rows() == 1) return $query->row_array();
        return NULL;
    }


    /**
     * Create new categorie record
     *
     * @user_id         int
     * @title           string
     * @parent          int
     * @return          array
     */
    function create_categorie($user_id,$title,$parent)
    {
        $data = array('title' => strtolower($title), 'parent' => $parent, 'user_ID' => $user_id);

        if ($this->db->insert($this->table_name, $data)) {
            $data = $this->get_categorie_by_id($this->db->insert_id() );
            return $data;
        }
        return NULL;
    }


    /**
     * Update  categorie record
     *
     * @user_id         int
     * @title           string
     * @parent          int
     * @data            array
     * @return          boolean
     */
    function update_categorie($user_id,$title,$parent,$data)
    {
        $this->db->where('LOWER(title)', strtolower($title));
        $this->db->where('user_ID', $user_id);
        $this->db->where('parent', $parent);

        if ($this->db->update($this->table_name, $data))
            return TRUE;

        return FALSE;
    }


    /**
     * Get or Create new categorie record
     *
     * @user_id         int
     * @title           string
     * @parent          int
     * @return          array
     */
    function getorcreate_categorie($user_id,$title,$parent)
    {
        $res=$this->get_categorie_by_title($user_id,$title,$parent);
        if (!$res) $res=$this->create_categorie($user_id,$title,$parent);

        return $res;
    }


    /**
     * Get categories
     *
     * @user_id         int
     * @title           string
     * @parent          int
     * @return          array
     */
    function get_categories($user_id)
    {
        $this->db->where('user_ID', $user_id);
        $this->db->order_by('parent');

        $query = $this->db->get($this->table_name);
        if ($query->num_rows() >= 1) return $query->result_array();
        return NULL;
    }

    // TODO! shared categorie gestion


} // END Class
