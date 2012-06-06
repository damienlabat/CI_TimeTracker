<ul class='records'>
<?php
if ( isset( $records ) ) {

    foreach ( $records as $k => $record ):

?>
   <?= record_li( $record, $user_name, array( 'duration' => 'full' ) ) ?>
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
