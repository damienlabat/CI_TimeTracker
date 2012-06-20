<?php

    // alerts and error messages

    if ( (isset($alerts)) && ($alerts!=FALSE) ) {
    foreach ($alerts as $k => $alert): ?>
        <div class="alert alert-<?=$alert['type']?>">
        <?=$alert['alert']?>
        </div>
    <?php endforeach;
    }

?>
