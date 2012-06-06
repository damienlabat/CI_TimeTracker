<h2><?= $tag[ 'tag' ] ?></h2>
<a class='btn btn-mini' href='<?=site_url('tt/'.$user_name.'/tag/'.$tag['id'].'/edit');?>'>edit</a>

<?php
$this->load->view( 'timetracker/records_list' );
$this->load->view( 'timetracker/tt_buttons' );