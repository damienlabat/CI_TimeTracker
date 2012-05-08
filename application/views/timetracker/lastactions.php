<ul class='activities'>
<?php
if (isset($last_actions)) {
    foreach ($last_actions as $k => $actions): ?>
    <?= activity_li($activions,$user_name, array('duration'=>'full') ) ?>
<?php endforeach;
}
?>
</ul>

