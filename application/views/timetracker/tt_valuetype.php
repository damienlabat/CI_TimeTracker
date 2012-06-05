<!--div id='activity' class="span4"><div class='cadre'-->
    <?php
//$this->load->view( 'timetracker/tt_tree' );
?>
<!--/div></div-->

<!--div id='categorie' class="span8"><div class='cadre'-->
    <h2><?= $valuetype[ 'title' ] ?></h2>
<?php
    if ( $valuetype['description']!='' ) echo "<div class='description'>".$valuetype['description']."</div>";
?>
    <a class='btn btn-mini' href='<?=site_url('tt/'.$user_name.'/valuetype/'.$valuetype['id'].'/edit');?>'>edit</a>

<!--/div></div-->

<?php
$this->load->view( 'timetracker/3blocks' );
$this->load->view( 'timetracker/tt_buttons' );