<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('activity_li'))
{
    function activity_li($activity)
    {


      $html="<li>
        <strong><a href='#".$activity['id']."'>".$activity['title']."</a><span class='arobase'>@</span>".categorie_path($activity['path_array'])."</strong>";

      if ($activity['running'])  $html.="<p>RUN <a href='".site_url('timetracker/stop/'.$activity['id'])."'>stop</a></p>";
        else $html="<p>duration: ".$activity['duration']."s</p>";

      $html.="  <p>start at: ".$activity['start_LOCAL']."</p>
        <p>unix time: ".$activity['start_UNIX']."</p>
        <p>description: ".$activity['description']."</p>
    </li>";
        return $html;
    }
}



if ( ! function_exists('categorie_path'))
{
    function categorie_path($categorie_path)
    {
      if (count($categorie_path)==1) $html= categorie_a($categorie_path[0]);
      else
      {
          $html="";
          foreach ($categorie_path as $k => $categorie) {
              if ($html!="") $html.="/";
              $html.= categorie_a($categorie);
          }
      }
      return $html;
    }
}



if ( ! function_exists('categorie_a'))
{
    function categorie_a($categorie)
    {
      $html="<a href='#".$categorie['slug']."'>".$categorie['title']."</a>";
      return $html;
    }
}

