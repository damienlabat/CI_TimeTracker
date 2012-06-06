<h2><?= $categorie[ 'title' ] ?></h2>
<?php

if ( $categorie['description']!='' ) echo "<div class='description'>".$categorie['description']."</div>";

?>
<a class='btn btn-mini' href='<?=site_url('tt/'.$user_name.'/categorie/'.$categorie['id'].'/edit');?>'>edit</a>
<?php

$this->load->view( 'timetracker/records_list' );
$this->load->view( 'timetracker/tt_buttons' );
