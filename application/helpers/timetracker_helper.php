<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('activity'))
{
    function activity_li($activity)
    {
      $html="<li>
        <strong>".$activity['title']."</strong>
        <p>start at: ".$activity['start_LOCAL']."</p>
        <p>description: ".$activity['description']."</p>
    </li>";
        return $html;
    }
}



