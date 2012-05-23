<?php if($record): ?>
<div id='activity' class="span4"><div class='cadre'>
    <?php
 $this->load->view('timetracker/tt_tree');
?>
</div></div>

<div id='record' class="span8"><div class='cadre'>
    <h2><?=$record['start_time']?> TODO change title</h2>
    <ul class='records'>
    <?=record_li($record,$user_name )?>
    </ul>
</div></div>
<?php endif; ?>

