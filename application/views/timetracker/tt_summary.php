<?php

//catgeorie

if (isset($stats['categorie'])){
    echo "<h2>categories</h2>";

    echo "records: <ul>";

    foreach ($stats['categorie'] as $ki => $item) {
        if ($item['title']=='') $item['title']='/root/';
        echo "<li><a href='".viz_url($user_name,'summary','categorie',$item['id'],$dates['uri'] )."'>".$item['title']."</a> => ".$item['count']." record(s), total time: ".duration2human($item['total'])."</li>";
    }

    echo "</ul><br/>";


    echo "<div class='camembert camembert_categorie'></div>";
}





// activity
$rubs=array('activity','todo','value','tag');


foreach ($rubs as $k => $rub)
if (isset($stats[$rub])){

    echo "<h2>".$rub."</h2>";


    if (isset($stats[ $rub.'_count' ])) {
        echo $stats[ $rub.'_count' ]." items<br/>";
        echo "Total time: ".duration2human($stats[ $rub.'_total' ])."<br/><br/>";
    }

    echo "records: <ul>";

    foreach ($stats[$rub] as $ki => $item) {
        echo "<li><a href='".viz_url($user_name,'summary','activity',$item['id'],$dates['uri'] )."'>".$item['activity_path']."</a> => ".$item['count']." record(s), total time: ".duration2human($item['total'])."</li>";
    }

    echo "</ul><br/>";


    echo "<div class='camembert camembert_".$rub."'></div>";



    if (isset($stats[$rub.'_tag'])) {
        echo "tags: <ul>";

        foreach ($stats[$rub.'_tag'] as $kt => $tag) {
            echo "<li><a href='".viz_url($user_name,'summary','tag',$kt,$dates['uri'] )."'>".$tag['tag']."</a> => ".$tag['count']." record(s), total time: ".duration2human($tag['total'])."</li>";
        }

        echo "</ul><br/>";

        echo "<div class='camembert camembert_".$rub."_tag'></div>";
    }



}
?>

<div class="btn-toolbar">

    <div class="btn-group">
        <a href='<?php echo viz_url($user_name,'records',$current['type_cat'],$current['id'],$dates['uri'] ) ?>' class="btn"> Records</a>
    </div>

    <div class="btn-group">
        <a href='<?php echo viz_url($user_name,'graph',$current['type_cat'],$current['id'],$dates['uri'] ) ?>' class="btn"><i class="icon-bar-chart"></i> Graph</a>
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




