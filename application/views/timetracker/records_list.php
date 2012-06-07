<?php
$this->load->view( 'timetracker/tab_buttons' );
?>

<table class='records table table-bordered'>
    <tbody>
<?php
if ( isset( $records ) ) {

    foreach ( $records as $k => $record ):

?>
   <?= record_tr( $record, $user_name, array( 'duration' => 'full' ) ) ?>
<?php

    endforeach;

}
?>
    <tbody>
</table>



<div class="pagination pagination-centered"><ul><?= $pager ?></ul></div>
