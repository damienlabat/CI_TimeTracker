<?php



    if (!isset($activities)) $activities=NULL;
    cat_content($cat_tree,$activities,$user_name);





  function cat_content($cat_array,$activities,$user_name) {

      echo "<ul>";
      foreach ($cat_array as $k => $cat)
            if (($cat['nb_act']+$cat['nb_cat']>0)&&($cat['isshow']>0)) {
              {
                  echo '<li><a href="'.site_url('tt/'.$user_name.'/categorie/'.$cat['id']).'"><i class="icon-folder-open"></i> '.$cat['title'].'</a></li>';
                  if (isset($cat['sub'])) cat_content($cat['sub'],$activities,$user_name);
                  if (isset($activities)) echo cat_activities($activities,$cat['id'],$user_name);
              }
          }
      echo "</ul>";
    }


  function cat_activities($activities_array,$cat_ID,$user_name) {

      $html='';
      foreach ($activities_array as $k => $activity) {
         if ($activity['categorie_ID']==$cat_ID) {
            $icon= 'icon-file';
            if ($activity['type_of_record']=='todo') { $icon='icon-exclamation-sign'; $activity['title']='!'.$activity['title']; }
            if ($activity['type_of_record']=='value') $icon='icon-flag';
            $html.= '<li><a href="'.site_url('tt/'.$user_name.'/'.$activity['type_of_record'].'/'.$activity['id']).'"><i class="'.$icon.'"></i> '.$activity['title'].'</a></li>';
        }

        }
      if ($html!='') return '<ul>'.$html.'</ul>';
    }

?>