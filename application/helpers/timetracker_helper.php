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

        /*  if ( $H%24 ==1 ) $html.= ($H % 24).'h ';
          if ( $H%24>1 )  $html.= ($H % 24).'h ';*/

          $html.= ($H % 24).':'.($M % 60);

          if (($duration<60)||($mode=='full')) $html.= ':'.($duration % 60);

      }
        return $html;
    }
}




if ( ! function_exists('date2human'))
{
    function date2human($date,$local_time='normal') // ADD timezone gestion
    {
        $unix=mysql_to_unix($date);
        $datestring = "%Y/%m/%d %h:%i";

        return mdate($datestring, $unix);
    }
}


//------------------------------------------------



if ( ! function_exists('record_li'))
{
    function record_li($record,$username,$param=array() )
    {

    if (!isset($param['duration'])) $param['duration']='normal'; // normal OR full (hide/show seconds for 1 minute min duration)


    $html= "<li class='record-item activity-".$record['type_of_record']."'>";

    $html.= record_time($record,$username);
    $html.= activity_path($record,$username);
    $html.= tag_list($record['tags'],$username);



   // if ($record['description']!='') $html.="  <p>description:<br/>".$record['description']."</p>";





    echo "</li>";
        return $html;
    }
}



if ( ! function_exists('record_time'))
{
    function record_time($record,$username)
    {
      $html=  '<div class="record-time">';

      $html.= '<span class="record-period">';
      $html.= '<a href="'.site_url('tt/'.$username.'/record/'.$record['id']).'"><time datetime=\''.$record['start_time'].'\'>'.date2human($record['start_time']).'</time>';
      if ((!$record['running'])&&($record['duration']>0))  $html.= ' - <time datetime=\''.$record['stop_at'].'\'>'.date2human($record['stop_at']).'</time>';
      $html.= '</a></span>';

      $html.= '<span class="record-duration">';

      if ($record['duration']>0) $html.= duration2human($record['duration']);
        else if (($record['type_of_record']!='value')&&(!$record['running'])) $html.="<br/><span class='label label-info'>PING!</span>";
         else if ($record['type_of_record']=='value') $html.="<br/><span class='label label-info'>Value</span>";

      if ($record['type_of_record']=='todo') {
            if ($record['running'])  $html.="<br/><span class='label label-warning'>TODO!</span>";
                else  $html.="<br/><span class='label label-success'>DONE!</span>";
        }

      $html.= '</span>';

      $html.= '</div>';

      return $html;
    }
}





if ( ! function_exists('activity_path'))
{
    function activity_path($record,$username)
    {

      if (!isset($record['activity_ID'])) $record['activity_ID']=$record['id'];
      $html= '<div class="activity-path">';
      $html.= '<span class="activity-item">';

      if ($record['type_of_record']=='todo') $html.= '!';

      if (isset($record['duration']))
        if (($record['type_of_record']!='value')&&($record['duration']==0))$html.= '.';

      $html.= "<a href='".site_url('tt/'.$username.'/'.$record['type_of_record'].'/'.$record['activity_ID'])."'>".$record['title']."</a>";
      if (isset($record['value']))  $html.=value($record['value'],$username);
      $html.= "</span>";

      $html.= categorie_path($record['path_array'],$username);

      if (isset($record['running'])) {
        $html.= '<div class="buttons btn-group">';
        $html.= "<a class='btn btn-mini' href='".site_url('tt/'.$username.'/record/'.$record['id'].'/edit')."'>edit</a>";
        if (!$record['running'])
            $html.= "<a class='btn btn-mini' href='".site_url('tt/'.$username.'/record/'.$record['id'].'/restart')."'>restart</a>";
        $html.= "<a class='btn btn-mini' href='".site_url('tt/'.$username.'/record/'.$record['id'].'/delete')."'>delete</a>";
        if ($record['running'])
              $html.= "<a class='btn btn-mini btn-inverse' href='".site_url('tt/'.$username.'/record/'.$record['id'].'/stop')."'>stop</a>";
        $html.= '</div>';
    }


      $html.= '</div>';
      return $html;
    }
}



if ( ! function_exists('categorie_path'))
{
    function categorie_path($categorie_path,$username)
    {
      $html='<span class="categorie-path">@';
      if (count($categorie_path)==1)
      {
          if ($categorie_path[0]['title']=='')   $html="";
          $html.= categorie_a($categorie_path[0],$username);
      }
      else
      {
          foreach ($categorie_path as $k => $categorie) {
              if ( $k>0 ) $html.="/";
              $html.= categorie_a($categorie,$username);
          }
      }
      $html.= '</span>';
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
      if ( isset($tag_array))
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