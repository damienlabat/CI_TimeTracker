<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Timetracker
{
    protected $user_id=null;

    function __construct()
    {
        $this->ci =& get_instance();

        $this->ci->load->library('tank_auth');
        $this->user_id= $this->ci->tank_auth->get_user_id();

        $this->ci->load->model('timetracker/tt_categories');
    }


/* CATEGORIES */

    function create_categories($categorie_string)
    {
        $cat_array=preg_split("/\//", $categorie_string);
        $parent=NULL;

        foreach ($cat_array as $k => $cat_title)
        {
            $res=$this->ci->tt_categories->getorcreate_categorie($this->user_id, $cat_title,$parent);
            $parent= $res['id'];
        }

        return $res;
    }


    function get_categories_tree()
    {
        $categories=$this->ci->tt_categories->get_categories($this->user_id);

        return  $this->recur_tree($categories,'parent',NULL);
    }



    function get_categories_path()
    {
        $res=array();
        $categories=$this->ci->tt_categories->get_categories($this->user_id);
        foreach ($categories as $k => $item)
            if ($item['parent']) $res[ $item['id'] ]= $res[ $item['parent'] ].'/'.$item['title'];
                else $res[ $item['id'] ]= $item['title'];

        sort($res);
        return $res;
    }

    function get_categorie_from_path($path)
    {
           // TODO!
    }




    function recur_tree($data,$var_root,$root){
        $res=array();
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



    function update_categorie($path,$data){
        $cat=$this->get_categorie_from_path($path);
        return $this->ci->tt_categories->update_categorie($this->user_id, $cat['title'],$cat['parent'],$data);
    }


}

/* End of file timetracker.php */
/* Location: ./application/libraries/timetracker.php */