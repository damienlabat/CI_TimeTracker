<h2><?= $tag[ 'tag' ] ?></h2>
<a class='btn btn-mini' href='<?=site_url('tt/'.$user['name'].'/tag/'.$tag['id'].'/edit');?>'>edit</a>

<?php
$this->load->view( 'timetracker/records_list' );
