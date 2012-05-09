<ul class='records'>
<?php
if (isset($todos)) {
    foreach ($todos as $k => $todo): ?>
    <?= record_li($todo,$user_name, array('duration'=>'full') ) ?>
<?php endforeach;
}
?>
</ul>

