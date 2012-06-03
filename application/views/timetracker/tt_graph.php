<script>data=<?=json_encode($records,JSON_NUMERIC_CHECK)?></script>
<pre>data=<?=json_encode($records,JSON_NUMERIC_CHECK)?>

<?php
print_r($current);
?>
</pre>

<div class="btn-toolbar">

    <div class="btn-group">
        <a href='<?php echo viz_url($user_name,'records',$current['type_cat'],$current['id'],$dates['uri'] ) ?>' class="btn"> Records</a>
    </div>

    <div class="btn-group">
        <a href='<?php echo viz_url($user_name,'summary',$current['type_cat'],$tag['id'],'all' ) ?>' class="btn"><i class="icon-list-alt"></i> Summary</a>
    </div>

    <div class="btn-group">
        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-download-alt"></i> Download <span class="caret"></span></a>
        <ul class="dropdown-menu">
            <li><a href='<?php echo viz_url($user_name,'export',$current['type_cat'],$current['id'],$dates['uri'],'json' ) ?>' target='_blank'>json</a></li>
            <li><a href='<?php echo viz_url($user_name,'export',$current['type_cat'],$current['id'],$dates['uri'],'csv' ) ?>' >csv</a></li>
            <li><a href='<?php echo viz_url($user_name,'export',$current['type_cat'],$current['id'],$dates['uri'],'txt' ) ?>' >txt</a></li>
          </ul>
    </div>

</div>