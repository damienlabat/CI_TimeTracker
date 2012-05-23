<ul class='records'>
<?php
if ( isset( $running_activities ) ) {

    foreach ( $running_activities as $k => $activity ):

?>
   <?= record_li( $activity, $user_name ) ?>
<?php

    endforeach;

}
?>
</ul>