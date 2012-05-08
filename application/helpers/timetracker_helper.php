<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');





if ( ! function_exists('duration2human'))
{
    function duration2human($duration,$mode='normal')
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

          if ($mode=='full') $html.= ' '.($duration % 60).' s';

      }
        return $html;
    }
}


//------------------------------------------------

if ( ! function_exists('activity_li'))
{
    function activity_li($activity,$username,$param=array() )
    {

    if (!isset($param['duration'])) $param['duration']='normal'; // normal OR full (hide/show seconds for 1 minute min duration)

      $html="<li><div class='activity-time'>".activity_time($activity)."</div>".activity_path($activity);

      if ($activity['running'])  $html.=" <a class='stop-btn btn btn-mini btn-inverse' href='".site_url('tt/'.$username.'/activity/'.$activity['id'].'/stop')."'>stop</a>";

    /*  if ( ($activity['duration']==0)&&(!$activity['running']) ) $html.="<span class='label label-info ping'>PING!</span>";
        else $html.="<p>duration: ".duration2human($activity['duration'],$param['duration'])."</p>";*/


      if (isset($activity['stop_at'])) $html.="  <p>stop at: ".$activity['stop_at']."</p>";

     // $html.="  <p>description: ".$activity['description']."</p>";
    echo "</li>";
        return $html;
    }
}



if ( ! function_exists('activity_time'))
{
    function activity_time($activity)
    {
      $html= $activity['start_UNIX'];
      if ($activity['running']) { /* ???*/ }
      else {
            if ($activity['duration']==0) $html.="<span class='label label-info ping'>PING!</span>";
            else $html.=" - ".$activity['stop_at'];
        }
      return $html;
    }
}



if ( ! function_exists('activity_path'))
{
    function activity_path($activity)
    {
      $html= " <strong class='activity_path'><span class='activity'>".$activity['title']."</span>".categorie_path($activity['path_array'])."</strong>";
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
      $html="<span class='category'>".$categorie['title']."</span>";
      return $html;
    }
}

