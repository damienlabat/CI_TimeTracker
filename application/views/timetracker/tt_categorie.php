<h2><?= format_categorie($categorie) ?></h2>
<?php

if ( $categorie['description']!='' ) echo "<div class='description'>".$categorie['description']."</div>";

if ( $categorie['title']!=''):
?>
<a class='btn btn-mini' href='<?=site_url('tt/'.$user['name'].'/categorie_'.$categorie['id'].'/edit');?>'>edit</a>
<?php
endif;
$this->load->view( 'timetracker/tt_menu' );
$this->load->view( 'timetracker/records_list' );