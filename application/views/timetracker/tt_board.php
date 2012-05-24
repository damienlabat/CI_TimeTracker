<!--div id='commandebloc' class="span12"><div class='cadre'-->
    <h2>Start a new activity</h2>
    <?php
$this->load->view( 'timetracker/form/main_form' );
?>
<!--/div></div-->



<?php
if ( isset( $running_activities ) ):
?>
<!--div class="span6"><div class='cadre'-->
    <h2>Running Activities</h2>
    <?php
    $this->load->view( 'timetracker/runningactivities' );
?>
<!--/div></div-->
<?php
endif;
?>


<?php
if ( isset( $todos ) ):
?>
<!--div class="span6"><div class='cadre'-->
    <h2>TODO</h2>
    <?php
    $this->load->view( 'timetracker/todos' );
?>
<!--/div></div-->
<?php
endif;
?>


<?php
if ( isset( $last_actions ) ):
?>
<!--div class="span12"><div class='cadre'-->
    <h2>Last actions</h2>
    <?php
    $this->load->view( 'timetracker/lastactions' );
?>
<!--/div></div-->
<?php
endif;
?>