<div id='activity' class="span4"><div class='cadre'>
    <?php
$this->load->view( 'timetracker/tt_tree' );
?>
</div></div>

<div id='activity' class="span8"><div class='cadre'>
    <h2><?= activity_path( $activity, $user_name ) ?></h2>


</div></div>