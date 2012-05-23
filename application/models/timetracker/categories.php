<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Categories extends CI_Model
{
    private $categories_table         = 'categories';
    private $activities_table         = 'activities';

    /**
     * Get categorie by Id
     *
     * @categorie_id    int
     * @return          array
     */
    function get_categorie_by_id($categorie_id)
    {
        $this->db->where('id', $categorie_id);

        $query = $this->db->get($this->categories_table);
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

        $query = $this->db->get($this->categories_table);
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

        if ($this->db->insert($this->categories_table, $data)) {
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

        if ($this->db->update($this->categories_table, $data))
            return TRUE;

        return FALSE;
    }


    function update_categorie_path($user_id,$path,$data){
        $cat=$this->getorcreate_categoriespath($user_id,$path);
        return $this->update_categorie($user_id, $cat['title'],$cat['parent'],$data);
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
        $this->db->order_by('parent,title');

        $query = $this->db->get($this->categories_table);
        if ($query->num_rows() >= 1) return $query->result_array();
        return NULL;
    }

    /*SELECT categories.*, count( DISTINCT activities.id) as nb_act, count(DISTINCT cat2.id) as nb_cat
FROM (`categories`)
LEFT JOIN activities ON categories.id=activities.categorie_ID
LEFT JOIN categories as cat2 ON categories.id=cat2.parent
WHERE categories.user_ID =  '1'
AND categories.isshow=1
GROUP BY categories.id
ORDER BY categories.`parent`, categories.`title`*/

     /**
     * Get categories with sub cat and activities count
     *
     * @user_id         int
     * @title           string
     * @parent          int
     * @return          array
     */
    function get_categories_with_count($user_id)
    {
        $this->db->select($this->categories_table.'.*');
        $this->db->select('count(distinct '.$this->activities_table.'.id) as nb_act');
        $this->db->select('count(distinct cat2.id) as nb_cat');

        $this->db->join($this->activities_table, $this->categories_table.'.id = '.$this->activities_table.'.categorie_ID', 'left');
        $this->db->join($this->categories_table.' as cat2', $this->categories_table.'.id = cat2.parent', 'left');

        $this->db->where($this->categories_table.'.user_ID', $user_id);
        $this->db->where($this->categories_table.'.isshow', 1);
        $this->db->group_by($this->categories_table.'.id');
        $this->db->order_by($this->categories_table.'.parent,'.$this->categories_table.'.title');

        $query = $this->db->get($this->categories_table);
        if ($query->num_rows() >= 1) return $query->result_array();
        return NULL;
    }

    // TODO! shared categorie gestion


    /* =============
     * action
     * =============*/


    function getorcreate_categoriespath($user_id,$path)
    {
        $cat_array=preg_split("/\//", $path);
        $parent=NULL;

        foreach ($cat_array as $k => $cat_title)
        {
            $res=$this->getorcreate_categorie($user_id, $cat_title, $parent);
            $parent= $res['id'];
        }

        return $res;
    }


    function get_categories_tree($user_id)
    {
        $categories=$this->get_categories_with_count($user_id);

        return  $this->recur_tree($categories,'parent',NULL);
    }



    function get_categories_path($user_id,$forceshow=FALSE)
    {
        $res=array();
        $categories=$this->get_categories($user_id);
        foreach ($categories as $k => $item)
        {
            if ( ($forceshow OR $item['show']) && ( isset($res[ $item['parent'] ]) OR !$item['parent']) )
            {
                if ($item['parent']) $res[ $item['id'] ]= $res[ $item['parent'] ].'/'.$item['title'];
                    else $res[ $item['id'] ]= $item['title'];
            }
        }

        sort($res);
        return $res;
    }



    function get_categorie_path_array($categorie_id)
    {
        $current_categorie= $this->get_categorie_by_id($categorie_id);
        $path_array= array($current_categorie);

        while (isset($current_categorie['parent'])) {
                $current_categorie= $this->get_categorie_by_id( $current_categorie['parent'] );
                $path_array[]= $current_categorie;
            }

        return array_reverse($path_array);
    }


    function get_categorie_from_path($user_id,$path)
    {
        $cat_array=preg_split("/\//", $path);
        $parent=NULL;
        $res=NULL;

        foreach ($cat_array as $k => $cat_title) {
                $res= $this->get_categorie_by_title($user_id, $cat_title, $parent);
                $parent=$res['id'];
            }
        return $res;
    }




    function recur_tree($data,$var_root,$root){
        $res=array();
        if ($data)
        foreach ($data as $k => $item)
            if ($item[$var_root] == $root)
            {
                 $sub= $this->recur_tree($data,$var_root,$item['id']);
                 if ($sub)  $item['sub']=$sub;
                 $res[]=$item;
             }
        if (count($res)>0) return $res;
        return NULL;
    }
} // END Class
