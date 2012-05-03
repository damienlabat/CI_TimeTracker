<ul class='activities'>
<?php foreach ($last_activities as $k => $activity): ?>
    <?= activity_li($activity) ?>
<?php endforeach; ?>
</ul>

<pre><?php print_r($last_activities) ?></pre>
