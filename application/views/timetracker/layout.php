<div class="row-fluid"><?php
//$this->load->view( 'timetracker/form/main_form' );

$this->load->view( 'timetracker/tt_cattree' );
$this->load->view( 'timetracker/tt_menu' );
?>
</div>
<?php

$this->load->view( 'timetracker/form/date_form' );
?>
<div class="page-header">
    <h1>Example page header <?=@$title?> <small>Subtext for header</small></h1>
</div>
<?php
if ( isset( $tt_layout ) )
    $this->load->view( 'timetracker/' . $tt_layout );


if ( isset( $TODO ) ):

?>
<div class='alert alert-block'><h1>TODO!!!!!</h1> <?= $TODO ?></div>
<?php

endif;
