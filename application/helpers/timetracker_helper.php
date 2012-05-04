<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');





if ( ! function_exists('duration2human'))
{
    function duration2human($duration)
    {
      if ( $duration<60 ) $html=$duration."s";
      else
      {
          $M= floor( $duration/60 );
          $H= floor( $M/60 );
          $day= floor( $H/24 );

          $html="";

          if ( $day==1 ) $html.= $day.' day ';
          if ( $day>1 )  $html.= $day.' days ';

          if ( $H%24 ==1 ) $html.= ($H % 24).' hour ';
          if ( $H%24>1 )  $html.= ($H % 24).' hours ';

          $html.= ($M % 60).' min';

      }
        return $html;
    }
}


//------------------------------------------------

if ( ! function_exists('activity_li'))
{
    function activity_li($activity)
    {


      $html="<li>".activity_path($activity);

      if ($activity['running'])  $html.=" <a class='stop-btn btn btn-mini btn-inverse' href='".site_url('timetracker/stop/'.$activity['id'])."'>stop</a>";

      if ( ($activity['duration']==0)&&(!$activity['running']) ) $html.="<span class='label label-info ping'>PING!</span>";
        else $html.="<p>duration: ".duration2human($activity['duration'])."</p>";

      $html.="  <p>start at: ".$activity['start_LOCAL']."</p>
        <p>unix time: ".$activity['start_UNIX']."</p>";

      if (isset($activity['stop_at'])) $html.="  <p>stop at: ".$activity['stop_at']."</p>";

      $html.="  <p>description: ".$activity['description']."</p>
    </li>";
        return $html;
    }
}


if ( ! function_exists('activity_path'))
{
    function activity_path($activity)
    {
      $html= "<strong class='activity_path'><a href='#".$activity['id']."'>".$activity['title']."</a>".categorie_path($activity['path_array'])."</strong>";
      return $html;
    }
}



if ( ! function_exists('categorie_path'))
{
    function categorie_path($categorie_path)
    {
      $html="<span class='arobase'>@</span>";
      if (count($categorie_path)==1)
      {
          if ($categorie_path[0]['title']=='')   $html="";
          $html.= categorie_a($categorie_path[0]);
      }
      else
      {
          foreach ($categorie_path as $k => $categorie) {
              if ( $k>0 ) $html.="<span class='slash'>/</span>";
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

