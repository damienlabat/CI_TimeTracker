<ul class='activities'>
<?php
if (isset($running_activities)) {
 foreach ($running_activities as $k => $activity): ?>
    <?= activity_li($activity,$user_name) ?>
<?php endforeach;
}
?>
</ul>
