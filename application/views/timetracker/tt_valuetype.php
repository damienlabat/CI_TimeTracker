<h2><?= $valuetype[ 'title' ] ?></h2>
<?php
    if ( $valuetype['description']!='' ) echo "<div class='description'>".$valuetype['description']."</div>";
?>
<a class='btn btn-mini' href='<?=site_url('tt/'.$user['name'].'/valuetype/'.$valuetype['id'].'/edit');?>'>edit</a>

<?php
$this->load->view( 'timetracker/records_list' );
