<ul class='activities'>
<?php
if (isset($last_activities)) {
    foreach ($last_activities as $k => $activity): ?>
    <?= activity_li($activity) ?>
<?php endforeach;
}
?>
</ul>

<!--pre><?php print_r($last_activities) ?></pre-->
