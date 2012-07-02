 <?php  if (isset($activity)) : ?>

<h2><?= activity_path( $activity, $user['name'] ) ?></h2>
<?php
if ( $activity['description']!='' ) echo "<div class='description'>".$activity['description']."</div>";
?>
<a class='btn btn-mini' href='<?=site_url('tt/'.$user['name'].'/'.$activity['type_of_record'].'/'.$activity['id'].'_edit');?>'>edit</a>

<?php endif;

$this->load->view( 'timetracker/records_list' );