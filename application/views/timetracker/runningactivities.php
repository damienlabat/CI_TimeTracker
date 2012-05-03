<ul class='activities'>
<?php foreach ($running_activities as $k => $activity): ?>
    <?= activity_li($activity) ?>
<?php endforeach; ?>
</ul>