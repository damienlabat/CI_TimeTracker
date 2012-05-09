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

if ( ! function_exists('record_li'))
{
    function record_li($record,$username,$param=array() )
    {

    //print_r($record);
    if (!isset($param['duration'])) $param['duration']='normal'; // normal OR full (hide/show seconds for 1 minute min duration)



    $html="<li class='activity-".$record['type_of_record']."'><div class='record-time'>".record_time($record)."</div>".activity_path($record,$username);

    if ($record['running']) {
          $html.="  <a class='stop-btn btn btn-mini btn-inverse' href='".site_url('tt/'.$username.'/'.$record['type_of_record'].'/'.$record['id'].'/stop')."'>stop</a>";

    }

    if ( $record['type_of_record']=='value')  $html.=value($record['value'],$username);
    if ( isset($record['tags']))  $html.=tag_list($record['tags'],$username);



      $html.="  <p>description: ".$record['description']."</p>";
      echo "</li>";
        return $html;
    }
}



if ( ! function_exists('record_time'))
{
    function record_time($record)
    {
      $html= $record['start_time'];
      if ($record['running']) { /* ???*/ }
      else {
            if ($record['duration']==0) $html.=" <span class='label label-info ping'>PING!</span>";
            else $html.=" - ".$record['stop_at'];
        }
      return $html;
    }
}



if ( ! function_exists('activity_path'))
{
    function activity_path($record,$username)
    {
      $html= " <strong class='activity_path'><a href='".site_url('tt/'.$username.'/'.$record['type_of_record'].'/'.$record['id'])."'>".$record['title']."</a>".categorie_path($record['path_array'],$username)."</strong>";
      return $html;
    }
}



if ( ! function_exists('categorie_path'))
{
    function categorie_path($categorie_path,$username)
    {
      $html="<span class='arobase'>@</span>";
      if (count($categorie_path)==1)
      {
          if ($categorie_path[0]['title']=='')   $html="";
          $html.= categorie_a($categorie_path[0],$username);
      }
      else
      {
          foreach ($categorie_path as $k => $categorie) {
              if ( $k>0 ) $html.="<span class='slash'>/</span>";
              $html.= categorie_a($categorie,$username);
          }
      }
      return $html;
    }
}



if ( ! function_exists('categorie_a'))
{
    function categorie_a($categorie,$username)
    {
      $html="<a href='".site_url('tt/'.$username.'/categorie/'.$categorie['id'])."' class='category'>".$categorie['title']."</a>";
      return $html;
    }
}


if ( ! function_exists('tag_list'))
{
    function tag_list($tag_array,$username)
    {
      $html="<ul class='tags'>";
      foreach ($tag_array as $k => $tag)
        $html.="<li>".tag($tag,$username)."</li>";
      $html.="</ul>";
      return $html;
    }
}


if ( ! function_exists('tag'))
{
    function tag($tag,$username)
    {
      $html= "<a href='".site_url('tt/'.$username.'/tag/'.$tag['id'])."'>".$tag['tag']."</a>";
      return $html;
    }
}




if ( ! function_exists('value'))
{
    function value($value,$username)
    {print_r($value);
      $html= "<div class='value'><a href='".site_url('tt/'.$username.'/valuetype/'.$value['id'])."'>#".$value['title']."</a> = <a href='".site_url('tt/'.$username.'/value/'.$value['record_ID'])."'>".$value['value']."</a></div>";
      return $html;
    }
}