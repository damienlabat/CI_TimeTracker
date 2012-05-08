<ul class='activities'>
<?php
if (isset($todos)) {
    foreach ($todos as $k => $todo): ?>
    <?= activity_li($todo,$user_name, array('duration'=>'full') ) ?>
<?php endforeach;
}
?>
</ul>

