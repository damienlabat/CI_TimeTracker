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

    if (!isset($param['duration'])) $param['duration']='normal'; // normal OR full (hide/show seconds for 1 minute min duration)



    $html="<li class='activity-".$record['type_of_record']."'><div class='record-time'>".record_time($record)."</div>".activity_path($record,$username);



    if ( $record['type_of_record']=='value')  $html.=value($record['value'],$username);
    if ( isset($record['tags']))  $html.=tag_list($record['tags'],$username);



    if ($record['description']!='') $html.="  <p>description:<br/>".$record['description']."</p>";


    $html.= "<br/>";
    $html.= "<a class='edit-btn btn btn-mini' href='".site_url('tt/'.$username.'/record/'.$record['id'].'/edit')."'>edit</a>";
    if (!$record['running'])
        $html.= " <a class='restart-btn btn btn-mini' href='".site_url('tt/'.$username.'/record/'.$record['id'].'/restart')."'>restart</a>";
    $html.= " <a class='delete-btn btn btn-mini' href='".site_url('tt/'.$username.'/record/'.$record['id'].'/delete')."'>delete</a>";
    if ($record['running'])
          $html.= " <a class='stop-btn btn btn-mini btn-inverse' href='".site_url('tt/'.$username.'/record/'.$record['id'].'/stop')."'>stop</a>";


    echo "</li>";
        return $html;
    }
}



if ( ! function_exists('record_time'))
{
    function record_time($record)
    {
      $html= $record['start_time'];
      if ($record['running']) {
           if ($record['type_of_record']=='todo') $html.=" <span class='label label-warning'>TODO!</span>";
           }
      else {
            if (($record['type_of_record']=='activity')&&($record['duration']==0)) $html.=" <span class='label label-info'>PING!</span>";
                else $html.=" - ".$record['stop_at'];
            if ($record['type_of_record']=='todo') $html.=" <span class='label label-success'>DONE!</span>";
        }
        if ($record['duration']>0) $html.="<br/>".duration2human($record['duration']);
      return $html;
    }
}



if ( ! function_exists('activity_path'))
{
    function activity_path($record,$username)
    {
      $html= " <strong class='activity_path'>";
      if ($record['type_of_record']=='todo') $html.= '<span class="todo-icon">!</span>';
      $html.= "<a href='".site_url('tt/'.$username.'/'.$record['type_of_record'].'/'.$record['id'])."'>".$record['title']."</a>".categorie_path($record['path_array'],$username)."</strong>";
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
    {
      $html= "<div class='value'><a href='".site_url('tt/'.$username.'/valuetype/'.$value['id'])."'>#".$value['title']."</a> = <a href='".site_url('tt/'.$username.'/value/'.$value['record_ID'])."'>".$value['value']."</a></div>";
      return $html;
    }
}