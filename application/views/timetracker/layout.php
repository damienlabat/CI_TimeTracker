<?php
if ( isset( $title ) ) echo "<h1>".$title."</h1>";
if ( isset( $tt_layout ) )
    $this->load->view( 'timetracker/' . $tt_layout );


if ( isset( $TODO ) ):

?>
<div class='alert alert-block'><h1>TODO!!!!!</h1> <?= $TODO ?></div>
<?php

endif;
