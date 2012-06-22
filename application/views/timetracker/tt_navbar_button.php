<?php   /* RUNNING ACTIVITIES */   ?>
<?php   if (count($running['activities'])==0): ?>
<li>
    <a href="#TODO">start new activity</a>
</li>
<?php   elseif (count($running['activities'])==1): ?>

<li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="badge badge-info"><?=count($running['activities'])?></span> activity <b class="caret"></b></a>
    <ul class="dropdown-menu">
        <li><a href='<?=site_url('tt/'.$user['name'].'/record/'.$running['activities'][0]['id'])?>'><i class='icon-flag'></i> <?=$running['activities'][0]['activity']['activity_path']?></a></li>
        <li class="divider"></li>
        <li><a href='<?=site_url('tt/'.$user['name'].'/record/'.$running['activities'][0]['id'])?>/edit'>edit</a></li>
        <li><a href='<?=site_url('tt/'.$user['name'].'/record/'.$running['activities'][0]['id'])?>/stop'>stop</a></li>
        <li><a href='#TODO'>stop &amp; start new</a></li>
        <li class="divider"></li>
        <li><a href='#TODO'>start new</a></li>
    </ul>
</li>

<?php   else: ?>

<li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="badge badge-info"><?=count($running['activities'])?></span> activities <b class="caret"></b></a>
    <ul class="dropdown-menu">
    <?php
        foreach($running['activities'] as $activity)
            echo "<li><a href='".site_url('tt/'.$user['name'].'/record/'.$activity['id'])."'><i class='icon-flag'></i> ".$activity['activity']['activity_path']." ".running_time($activity)."</a></li>";
        ?>
        <li class="divider"></li>
        <li><a href='#TODO'>stop all</a></li>
        <li class="divider"></li>
        <li><a href='#TODO'>start new</a></li>
    </ul>
</li>

<?php   endif; ?>






<?php   /* RUNNING TODO */   ?>
<?php   if (count($running['todos'])==0): ?>
<li>
    <a href="#TODO">add new todo</a>
</li>
<?php   elseif (count($running['todos'])==1): ?>

<li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="badge badge-warning"><?=count($running['todos'])?></span> thing todo <b class="caret"></b></a>
    <ul class="dropdown-menu">
        <li><a href='<?=site_url('tt/'.$user['name'].'/record/'.$running['todos'][0]['id'])?>'><i class='icon-exclamation-sign'></i> <?=$running['todos'][0]['activity']['activity_path']?></a></li>
        <li class="divider"></li>
        <li><a href='<?=site_url('tt/'.$user['name'].'/record/'.$running['todos'][0]['id'])?>/edit'>edit</a></li>
        <li><a href='<?=site_url('tt/'.$user['name'].'/record/'.$running['todos'][0]['id'])?>/stop'>mark it done</a></li>
        <li class="divider"></li>
        <li><a href='#TODO'>add new</a></li>
    </ul>
</li>

<?php   else: ?>

<li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="badge badge-warning"><?=count($running['todos'])?></span> things todo <b class="caret"></b></a>
    <ul class="dropdown-menu">
    <?php
        foreach($running['todos'] as $todo)
            echo "<li><a href='".site_url('tt/'.$user['name'].'/record/'.$todo['id'])."'><i class='icon-exclamation-sign'></i> ".$todo['activity']['activity_path']."</a></li>";
        ?>
        <li class="divider"></li>
        <li><a href='#TODO'>mark all done</a></li>
        <li class="divider"></li>
        <li><a href='#TODO'>add new</a></li>
    </ul>
</li>

<?php   endif; ?>



<li>
    <a href="#TODO">add new record</a>
</li>