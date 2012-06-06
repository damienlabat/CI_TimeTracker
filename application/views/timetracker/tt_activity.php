 <?php  if (isset($activity)) : ?>

<h2><?= activity_path( $activity, $user_name ) ?></h2>
<?php
if ( $activity['description']!='' ) echo "<div class='description'>".$activity['description']."</div>";
?>
<a class='btn btn-mini' href='<?=site_url('tt/'.$user_name.'/'.$activity['type_of_record'].'/'.$activity['id'].'/edit');?>'>edit</a>

<?php endif;





$this->load->view( 'timetracker/records_list' );
$this->load->view( 'timetracker/tt_buttons' );