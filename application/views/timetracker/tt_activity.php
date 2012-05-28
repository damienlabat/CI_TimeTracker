<!--div id='activity' class="span4"><div class='cadre'-->
    <?php
$this->load->view( 'timetracker/tt_tree' );
?>
<!--/div></div-->

<!--div id='activity' class="span8"><div class='cadre'-->
    <h2><?= activity_path( $activity, $user_name ) ?></h2>
    <?php
    if ( $activity['description']!='' ) echo "<div class='description'>".$activity['description']."</div>";
    ?>
    <a class='btn btn-mini' href='<?=site_url('tt/'.$user_name.'/activity/'.$activity['id'].'/edit');?>'>edit</a>


<!--/div></div-->

<?php
$this->load->view( 'timetracker/3blocks' );