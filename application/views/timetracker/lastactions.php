<ul class='activities'>
<?php
if (isset($last_actions)) {
    foreach ($last_actions as $k => $action): ?>
    <?= activity_li($action,$user_name, array('duration'=>'full') ) ?>
<?php endforeach;
}
?>
</ul>
