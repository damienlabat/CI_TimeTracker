<?php


$rubs=array('activity','todo','value','tag');


foreach ($rubs as $k => $rub)
if (isset($stats[$rub])){

    echo "<h2>Summary for ".$rub."</h2>";


    echo $stats[ $rub.'_count' ]." items<br/>";
    echo "Total time: ".duration2human($stats[ $rub.'_total' ])."<br/><br/>";
    echo "records: <ul>";

    foreach ($stats[$rub] as $ki => $item) {
        echo "<li>".$item['activity_path']." => ".$item['count']." record(s), total time: ".duration2human($item['total'])."</li>";
    }

    echo "</ul><br/>";


    if ($stats[ $rub.'_total' ]>0) {
        echo "<table class='camembert table table-bordered'>
            <thead><tr><th>id</th><th>path</th><th>pp</th></tr></thead>
            <tbody>";
        foreach ($stats[$rub] as $ki => $item) echo "<tr><td>".$item['id']."</td><td>".$item['activity_path']."</td><td>".($item['total']/$stats[ $rub.'_total' ])."</td></tr>";
        echo "</tbody></table>";
    }



    if (isset($stats[$rub.'_tag'])) {
        echo "tags: <ul>";

        foreach ($stats[$rub.'_tag'] as $kt => $tag) {
            echo "<li>".$tag['tag']." => ".$tag['count']." record(s), total time: ".duration2human($tag['total'])."</li>";
        }

        echo "</ul><br/>";

        if ($stats[ $rub.'_tag_total' ]>0) {
            echo "<table class='camembert table table-bordered'>
            <thead><tr><th>id</th><th>tag</th><th>pp</th></tr></thead>
            <tbody>";
            foreach ($stats[$rub.'_tag'] as $kt => $tag) echo "<tr><td>".$kt."</td><td>".$tag['tag']."</td><td>".($tag['total']/$stats[ $rub.'_tag_total' ])."</td></tr>";
            echo "</tbody></table>";
        }

    }

}

?>
<pre><?php @print_r($dates); ?></pre>
<pre><?php @print_r($stats); ?></pre>



