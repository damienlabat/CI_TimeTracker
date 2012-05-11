<?php
    if (isset($tt_layout))    $this->load->view('timetracker/'.$tt_layout);
?>
<?php if (isset($TODO)) : ?><div class='span12 alert alert-block'><h1>TODO</h1> <?=$TODO?></div><?php endif; ?>
