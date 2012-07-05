<div id='alertzone'>
	<?php

    // alerts and error messages

    if ( (isset($alerts)) && ($alerts!=FALSE) ) {
    foreach ($alerts as $k => $alert): ?>
        <div class="alert alert-<?=$alert['type']?>">
        <?=$alert['alert']?>
        <a class="close" data-dismiss="alert" href="#">&times;</a>
        </div>
    <?php endforeach;
    }

?>
</div>
