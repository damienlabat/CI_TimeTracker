<?php

if (isset($breadcrumb))
    if (count($breadcrumb)>=1) {  // virer quand 1 seul niveau ?

    echo '<ul class="breadcrumb">';

    foreach ($breadcrumb as $k => $breadcrumb_item) {

        if (($breadcrumb_item['url'])&&($k<count($breadcrumb)-1)) echo '<li><a href="'.site_url($breadcrumb_item['url']).'">'.$breadcrumb_item['title'].'</a> <span class="divider">/</span></li>';
            else echo '<li class="active">'.$breadcrumb_item['title'].'</li>';
        }

    echo '</ul>';
}

?>