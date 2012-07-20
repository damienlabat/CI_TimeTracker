<div class="window activities">
    <h1><a href='<?=site_url('tt/'.$user['name'].'/activities')?>'>Activities</a></h1>

    <div class="row-fluid">
        <div class='span6'>
            <h6>Running</h6>
                <?php

    if ( count($running['activities'])>0 ):

    ?>


        <table class='table'>
        <?php

        foreach($running['activities'] as $record) {
                echo "<tr>";
                echo "<td><a href='".site_url('tt/'.$user['name'].'/record_'.$record['id'])."'>".$record['activity']['activity_path']."</a></td>";
                echo "<td>".running_time($record)."</td>";
                echo "<td><a href='".site_url('tt/'.$user['name'].'/record_'.$record['id'])."/stop' class='btn btn-danger btn-mini'>STOP</a></td>";
                echo "</tr>";
            }

        ?>
        </table>





    <?php else: ?>


    <?php endif; ?>
    
    <a id='new_activity_button' href='<?=site_url('tt/'.$user['name'].'/activity/new')?>' class='btn btn-primary'>Add new activity</a>
    <div id='new_activity_ajax'></div>
        </div>
        
    <?php

    if ( count($last_activities)>0 ):

    ?>

        <div class='span6'>
            <h6>Last activities</h6>
           <table class='table'>
    <?php

        foreach($last_activities as $record) {
                echo "<tr>";
                echo "<td>".$record['start_time']."</td>";
                echo "<td><a href='".site_url('tt/'.$user['name'].'/activity_'.$record['activity']['id'])."'>".$record['activity']['activity_path']."</a></td>";
                echo "<td>".duration2human($record['duration'])."</td>";
                echo "<td><a href='".site_url('tt/'.$user['name'].'/record_'.$record['id'])."/restart' class='btn btn-mini'>RESTART</a></td>";

                echo "</tr>";
                }

    ?>
    </table>
    
    <?php

    if ( $tt_layout == 'tt_home' ) :

    ?>

        <div class="btn-group pull-right">
          <button data-toggle="dropdown" class="btn dropdown-toggle">view more <span class="caret"></span></button>
          <ul class="dropdown-menu">
            <li><a href="<?=site_url('tt/'.$user['name'].'/activities')?>">last activities</a></li>
            <li><a href="<?=site_url('tt/'.$user['name'].'/activities/summary')?>">summary</a></li>
            <li><a href="<?=site_url('tt/'.$user['name'].'/activities/graph')?>">graph</a></li>
          </ul>
        </div>
    <?php
        else: 


        endif;
    endif;

    ?>

        </div>

    </div>

</div>
