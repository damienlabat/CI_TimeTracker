 <?php
$this->load->view( 'timetracker/tt_tree' );



if (isset($activity)) : ?>

    <h2><?= activity_path( $activity, $user_name ) ?></h2>
    <?php
    if ( $activity['description']!='' ) echo "<div class='description'>".$activity['description']."</div>";
    ?>
    <a class='btn btn-mini' href='<?=site_url('tt/'.$user_name.'/activity/'.$activity['id'].'/edit');?>'>edit</a>

<?php
endif;





$this->load->view( 'timetracker/3blocks' );
$this->load->view( 'timetracker/tt_buttons' );