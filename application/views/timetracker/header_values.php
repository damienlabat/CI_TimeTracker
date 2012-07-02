<div class='window values<?php
if ($tt_layout=='tt_home') echo ' span6';
?>'>
    <h1><a href='<?=site_url('tt/'.$user['name'].'/values')?>'>Values</a></h1>
        <?php

    if ( count($last_values)>0 ):

    ?>
        <h6>last values</h6>
        <table class='table'>
    <?php

        foreach($last_values as $last_values_item) {
                echo "<tr>";
                echo "<td>".$last_values_item['start_time']."</td>";
                echo "<td><a href='".site_url('tt/'.$user['name'].'/record_'.$last_values_item['id'])."'>".$last_values_item['activity']['activity_path'].value( $last_values_item ).'</a>'."</td>";

                echo "</tr>";
                }

    ?>
    </table>
    <?php if ($tt_layout=='tt_home'): ?>
     <div class="btn-group pull-right">
          <button data-toggle="dropdown" class="btn dropdown-toggle">view more <span class="caret"></span></button>
          <ul class="dropdown-menu">
            <li><a href="<?=site_url('tt/'.$user['name'].'/values')?>">last values</a></li>
            <li><a href="<?=site_url('tt/'.$user['name'].'/values/graph')?>">graph</a></li>
          </ul>
        </div>
    <?php endif; ?>
<?php endif; ?>
    <a href='<?=site_url('tt/'.$user['name'].'/value/new')?>' class='btn btn-success'>Record new value</a>
</div>