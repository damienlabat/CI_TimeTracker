<?php
$this->load->view( 'timetracker/tab_buttons' );
$records=$list[ $current['tab'] .'_records'];
?>

<table class='records table'>
    <thead>
        <tr><th>Time</th><th>Title</th><th>Tags</th><th>Actions</th></tr>
    </thead>
    <tbody>
<?php
if ( isset( $records ) ) {

    foreach ( $records as $k => $record ):

?>
   <?= record_tr( $record, $user['name'], array( 'duration' => 'full' ) ) ?>
<?php

    endforeach;

}
?>
    <tbody>
</table>



<div class="pagination pagination-centered"><ul><?= $pager ?></ul></div>