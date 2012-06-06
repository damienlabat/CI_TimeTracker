<?php

//catgeorie
/* sert a rien ?? si pas trie par type of records
if (isset($stats['categorie'])){
    echo "<h2>categories</h2><div class='row'><div class='span6'>";

    echo "records:<table class='table'>
    <thead>
    <tr><th>categorie</th><th>records count</th><th>total time</th></tr>
    </thead>
    <tbody>";

    foreach ($stats['categorie'] as $ki => $item) {
        if ($item['title']=='') $item['title']='/root/';
        echo "<tr><td><a href='".tt_url($user_name,'summary','categorie',$item['id'],$dates['uri'] )."'>".$item['title']."</a></td><td>".$item['count']."</td><td>".duration2human($item['total'])."</td></tr>";
    }

    echo "</tbody></table></div>";


    echo "<div class='camembert camembert_categorie span6'></div></div>";
}

*/



// activity
$rubs=array('activity','todo','value','tag');


foreach ($rubs as $k => $rub)
if (isset($stats[$rub])){

    echo "<h2>".$rub."</h2><div class='row'><div class='span6'>";


    if (isset($stats[ $rub.'_count' ])) {
        echo $stats[ $rub.'_count' ]." items<br/>";
        echo "Total time: ".duration2human($stats[ $rub.'_total' ])."<br/>";
    }

    echo "records:<table class='table'>
    <thead>
    <tr><th>categorie</th><th>records count</th><th>total time</th></tr>
    </thead>
    <tbody>";

    foreach ($stats[$rub] as $ki => $item)
        echo "<tr><td><a href='".tt_url($user_name,'summary',$item['type_of_record'],$item['id'],$dates['uri'] )."'>".$item['activity_path']."</a></td><td>".$item['count']."</td><td>".duration2human($item['total'])."</td></tr>";


    echo "</tbody></table></div>";


    echo "<div class='span6 camembert camembert_".$rub."'></div></div>";



    if (isset($stats[$rub.'_tag'])) {
        echo "<div class='row'><div class='span6'>tags:<table class='table'>
    <thead>
    <tr><th>categorie</th><th>records count</th><th>total time</th></tr>
    </thead>
    <tbody>";

        foreach ($stats[$rub.'_tag'] as $kt => $tag) {
            echo "<tr><td><a href='".tt_url($user_name,'summary','tag',$kt,$dates['uri'] )."'>".$tag['tag']."</a></td><td>".$tag['count']."</td><td>".duration2human($tag['total'])."</td></tr>";
        }

        echo "</tbody></table></div>";

        echo "<div class='span6 camembert camembert_".$rub."_tag'></div></div>";
    }



}

$this->load->view( 'timetracker/tt_buttons' );





