<h2><?= $tag[ 'tag' ] ?></h2>
<a class='btn btn-mini' href='<?=site_url('tt/'.$user['name'].'/tag_'.$tag['id'].'/edit');?>' data-toggle='modal'>edit</a>

<?php
$this->load->view( 'timetracker/tt_menu' );
$this->load->view( 'timetracker/records_list' );
