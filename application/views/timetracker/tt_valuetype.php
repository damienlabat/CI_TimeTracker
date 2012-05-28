<!--div id='activity' class="span4"><div class='cadre'-->
    <?php
//$this->load->view( 'timetracker/tt_tree' );
?>
<!--/div></div-->

<!--div id='categorie' class="span8"><div class='cadre'-->
    <h2><?= $value_type[ 'title' ] ?></h2>
<?php
    if ( $value_type['description']!='' ) echo "<div class='description'>".$value_type['description']."</div>";
?>
    <a class='btn btn-mini' href='<?=site_url('tt/'.$user_name.'/valuetype/'.$value_type['id'].'/edit');?>'>edit</a>

<!--/div></div-->

<?php
$this->load->view( 'timetracker/3blocks' );
