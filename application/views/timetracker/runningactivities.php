todo!
<ul>
<?php foreach ($running_activities as $k => $activity): ?>
    <li>
        <strong><?=$activity['title']?></strong>
        <p>start at: <?=$activity['start_LOCAL']?></p>
        <p>description: <?=$activity['description']?></p>
    </li>
<?php endforeach; ?>
</ul>
<pre><?php print_r($running_activities) ?></pre>