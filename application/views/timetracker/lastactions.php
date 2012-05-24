<ul class='records'>
<?php
if ( isset( $last_actions ) ) {

    foreach ( $last_actions as $k => $action ):

?>
   <?= record_li( $action, $user_name, array( 'duration' => 'full' ) ) ?>
<?php

    endforeach;

}
?>
</ul>

<div class="pagination pagination-centered"><ul>
<?php
    echo $pager;
?>
</ul></div>