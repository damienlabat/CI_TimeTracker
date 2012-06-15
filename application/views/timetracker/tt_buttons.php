
<nav class="btn-toolbar">

<?php if ($current['action']<>'record'): ?>
    <div class="btn-group">
        <a href='<?php echo tt_url($user_name,'records',$current ) ?>' class="btn"> Records</a>
    </div>
<?php endif ?>

<?php if ($current['action']<>'summary'): ?>
    <div class="btn-group">
        <a href='<?php echo tt_url($user_name,'summary',$current ) ?>' class="btn"><i class="icon-list-alt"></i> Summary</a>
    </div>
<?php endif ?>

<?php if ($current['action']<>'graph'): ?>
    <div class="btn-group">
        <a href='<?php echo tt_url($user_name,'graph',$current ) ?>' class="btn"><i class="icon-bar-chart"></i> Graph</a>
    </div>
<?php endif ?>

    <div class="btn-group">
        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-download-alt"></i> Download <span class="caret"></span></a>
        <ul class="dropdown-menu">
            <li><a href='<?php echo tt_url($user_name,'export',$current, array('format'=>'json') ) ?>' target='_blank'>json</a></li>
            <li><a href='<?php echo tt_url($user_name,'export',$current, array('format'=>'csv') ) ?>' >csv</a></li>
            <li><a href='<?php echo tt_url($user_name,'export',$current, array('format'=>'txt') ) ?>' >txt</a></li>
          </ul>
    </div>

</nav><?php
