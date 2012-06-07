<?php if (isset($tabs)) : ?>
    <ul class="nav nav-tabs">
    <?php

        foreach ( $tabs as $k => $tab ) {
            if ( element( 'active', $tab )) echo '<li class="active"><a href="#">'.$tab['title'].'</a></li>';
                else  echo '<li><a href="'.$tab['url'].'">'.$tab['title'].'</a></li>';
        }

     ?>
    </ul>
<?php

endif;


?>