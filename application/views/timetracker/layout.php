
<?php if ($title != '') : ?>
<div class="page-header">
    <h1><?=@$title?><?php
    if ($subtitle != '') echo " <small>".$subtitle."</small>";
    ?></h1>
</div>
<?php
endif;

if ( isset( $tt_layout ) )
    $this->load->view( 'timetracker/' . $tt_layout );


if ( isset( $TODO ) ):

?>
<div class='alert alert-block'><h1>DEV TODO!!!!!</h1> <?= $TODO ?></div>
<?php

endif;
