<?php

if (isset($breadcrumb))
    if (count($breadcrumb)>1) {

    echo '<ul class="breadcrumb">';

    foreach ($breadcrumb as $k => $breadcrumb_item) {

        if (($breadcrumb_item['url'])&&($k<count($breadcrumb)-1)) echo '<li><a href="'.$breadcrumb_item['url'].'">'.$breadcrumb_item['title'].'</a> <span class="divider">/</span></li>';
            else echo '<li class="active">'.$breadcrumb_item['title'].'</li>';
        }

    echo '</ul>';
}

?>