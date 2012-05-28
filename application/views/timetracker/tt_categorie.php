<!--div id='activity' class="span4"><div class='cadre'-->
    <?php
$this->load->view( 'timetracker/tt_tree' );
?>
<!--/div></div-->

<!--div id='categorie' class="span8"><div class='cadre'-->
    <h2><?= $categorie[ 'title' ] ?></h2>
<?php
    if ( $categorie['description']!='' ) echo "<div class='description'>".$categorie['description']."</div>";
?>
    <a class='btn btn-mini' href='<?=site_url('tt/'.$user_name.'/categorie/'.$categorie['id'].'/edit');?>'>edit</a>

<!--/div></div-->

<?php
$this->load->view( 'timetracker/3blocks' );
