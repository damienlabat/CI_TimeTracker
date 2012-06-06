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

          $M=$M % 60;
          if ($M<10) $M='0'.$M;

          $html.= ($H % 24).':'.$M;

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


    $html= "<li class='record-item activity-".$record['activity']['type_of_record']."'>";

    $html.= record_time($record,$username);
    $html.= activity_path($record['activity'],$username);
    $html.= value($record,$username);
    $html.= tag_list($record['tags'],$username);
    $html.= record_buttons($record,$username);

    if ($record['description']!='') $html.="  <p>description:<br/>".$record['description']."</p>";


    echo "</li>";
        return $html;
    }
}



if ( ! function_exists('record_div'))
{
    function record_div($record,$username,$param=array() )
    {

    if (!isset($param['duration'])) $param['duration']='normal'; // normal OR full (hide/show seconds for 1 minute min duration)


    $html= "<div class='record-item activity-".$record['activity']['type_of_record']."'>";

    $html.= record_time($record,$username);
    $html.= activity_path($record['activity'],$username);
    $html.= value($record,$username);
    $html.= tag_list($record['tags'],$username);
    $html.= record_buttons($record,$username,TRUE);

    if ($record['description']!='') $html.="  <p>description:<br/>".$record['description']."</p>";


    echo "</div>";
        return $html;
    }
}



if ( ! function_exists('record_time'))
{
    function record_time($record,$username)
    {
      $html=  '<span class="record-time">';

      $html.= '<span class="record-period">';
      $html.= '<a href="'.site_url('tt/'.$username.'/record/'.$record['id']).'"><time datetime=\''.$record['start_time'].'\'>'.date2human($record['start_time']).'</time>';
      if ((!$record['running'])&&($record['duration']>0))  $html.= ' - <time datetime=\''.$record['stop_at'].'\'>'.date2human($record['stop_at']).'</time>';
      $html.= '</a></span>';

      $html.= ' <span class="record-duration">';

      if (($record['duration']>0)OR($record['running']==1)) $html.= duration2human($record['duration']);
        else if (($record['activity']['type_of_record']!='value')&&(!$record['running'])) $html.=" <span class='label label-info'>PING!</span>";
         else if ($record['activity']['type_of_record']=='value') $html.=" <span class='label label-info'>Value</span>";

      if ($record['activity']['type_of_record']=='todo') {
            if ($record['running'])  $html.=" <span class='label label-warning'>TODO!</span>";
                else  $html.=" <span class='label label-success'>DONE!</span>";
        }

      $html.= '</span>';

      $html.= '</span>';

      return $html;
    }
}





if ( ! function_exists('activity_path'))
{
    function activity_path($activity,$username)
    {

      $html= ' <span class="activity-path">';
      $html.= '<span class="activity-item">';

      if ($activity['type_of_record']=='todo') $html.= '!';

      $html.= "<a href='".site_url('tt/'.$username.'/'.$activity['type_of_record'].'/'.$activity['id'])."'>".$activity['title']."</a>";
     // if (isset($activity['value']))  $html.=value($record['value'],$username);
      $html.= "</span>";

      $html.= categorie_a($activity['categorie'],$username);

      $html.= '</span>';
      return $html;
    }
}




if ( ! function_exists('record_buttons'))
{
    function record_buttons($record,$username,$show_delete=FALSE)
    {

      $html= '';


      if (isset($record['running'])) {

        $html.= ' <span class="buttons btn-group">';
        $html.= "<a class='btn btn-mini' href='".site_url('tt/'.$username.'/record/'.$record['id'].'/edit')."'><i class='icon-pencil'></i> edit</a>";
        if (!$record['running'])
            $html.= "<a class='btn btn-mini' href='".site_url('tt/'.$username.'/record/'.$record['id'].'/restart')."'><i class='icon-play'></i> restart</a>";
        if ($show_delete) $html.= "<a class='btn btn-mini' href='".site_url('tt/'.$username.'/record/'.$record['id'].'/delete')."'><i class='icon-trash'></i> delete</a>";
        if ($record['running'])
              $html.= "<a class='btn btn-mini' href='".site_url('tt/'.$username.'/record/'.$record['id'].'/stop')."'><i class='icon-stop'></i> stop</a>";
        $html.= '</span>';

          if (element('delete_confirm',$record)==TRUE)    $html.= "<div><a class='btn btn-mini btn-danger' href='".site_url('tt/'.$username.'/record/'.$record['id'].'/delete?delete=true')."'><i class='icon-trash'></i> delete ! confirmed ?</a></div>";
    }


      return $html;
    }
}






if ( ! function_exists('categorie_a'))
{
    function categorie_a($categorie,$username)
    {
      if ($categorie['title']=='')  return '';

      $html="@<a href='".site_url('tt/'.$username.'/categorie/'.$categorie['id'])."' class='category'>".$categorie['title']."</a>";
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
    function value($record,$username) {
        $html='';
        if (isset($record['value']))
            $html= "<div class='value'><a href='".site_url('tt/'.$username.'/valuetype/'.$record['value']['valuetype_ID'])."'>#".$record['value']['title']."</a> = <a href='".site_url('tt/'.$username.'/record/'.$record['id'])."'>".$record['value']['value']."</a></div>";

        return $html;
    }
}




if ( ! function_exists('tt_url'))
{
    function tt_url($username,$type,$cat=NULL,$id=NULL,$dateuri=NULL,$graph=NULL )
    {
        $url='tt/'.$username.'/'.$type;

        if ($dateuri==NULL) $dateuri='all';
        if ($id=='NULL') $id='all';

        if ($cat!==NULL) $url.='/'.$cat;
        if ($id!==NULL) $url.='/'.$id;
        if ($dateuri!==NULL) $url.='/'.$dateuri;
        if ($graph!==NULL) $url.='/'.$graph;


        if ($type=='records') {
             if ($cat==NULL) $url='tt/'.$username;
             elseif ($id=='all') $url='tt/'.$username.'/'.$cat;
             else  $url='tt/'.$username.'/'.$cat.'/'.$id;

            }

        return site_url($url);
    }
}





if ( ! function_exists('draw_text_table'))
{
    function draw_text_table ($table) {

        // Work out max lengths of each cell

        foreach ($table AS $row) {
            $cell_count = 0;
            foreach ($row AS $key=>$cell) {

                $cell=str_replace( array("\r","\n"), " ", $cell);
                if (!is_array($cell)) $cell_length = strlen($cell);
                    else $cell_length = 0;

                $cell_count++;
                if (!isset($cell_lengths[$key]) || $cell_length > $cell_lengths[$key]) $cell_lengths[$key] = $cell_length;

                if (!isset($cell_show[$key])) $cell_show[$key]=FALSE;
                if ($cell_length>0) $cell_show[$key]=TRUE;

            }
        }

        // Build header bar

        $bar = '+';
        $header = '|';
        $i=0;

        foreach ($cell_lengths AS $fieldname => $length) {
            if ($cell_show[$fieldname]) {
                $name = $fieldname;
                if (strlen($name) > $length) {
                    // crop long headings

                    //$name = substr($name, 0, $length-1);
                    $cell_lengths[$fieldname]=strlen($name);

                }
                $bar .= str_pad('', $cell_lengths[$fieldname]+2, '-')."+";
                $header .= ' '.str_pad($name,  $cell_lengths[$fieldname], ' ', STR_PAD_RIGHT) . " |";
            }
        }

        $output = '';

        $output .= $bar."\n";
        $output .= $header."\n";

        $output .= $bar."\n";

        // Draw rows

        foreach ($table AS $row) {
            $output .= "|";

            foreach ($row AS $key=>$cell) {
                if ($cell_show[$key]) {
                    $cell=str_replace( array("\r","\n"), " ", $cell);
                    $output .= ' '.str_pad($cell, $cell_lengths[$key], ' ', STR_PAD_RIGHT) . " |";
                }

            }
            $output .= "\n";
        }

        $output .= $bar."\n";

        return $output;

    }
}