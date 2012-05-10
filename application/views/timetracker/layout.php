<?php
    if (isset($tt_layout))    $this->load->view('timetracker/'.$tt_layout);
?>
<?php if (isset($TODO)) : ?><h1><?=$TODO?></h1><?php endif; ?>
