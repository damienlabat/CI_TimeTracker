<div class="window activities">
    <h1>Activities</h1>

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
                echo "<td><a href='".site_url('tt/'.$user['name'].'/activity_'.$record['activity']['id'])."'>".$record['activity']['activity_path']."</a></td>";
                echo "<td>".running_time($record)."</td>";
                echo "<td><a href='".site_url('tt/'.$user['name'].'/record_'.$record['id'])."/stop' class='btn btn-danger btn-mini'>STOP</a></td>";
                echo "</tr>";
            }

        ?>
        </table>





    <?php else: ?>


    <?php endif; ?>
    <a href='<?=site_url('tt/'.$user['name'].'/activity/new')?>' class='btn btn-success'>Add new activity</a>
        </div>

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
    <a class='pull-right' href='<?=site_url('tt/'.$user['name'].'/activities')?>'>view more</a>
        </div>

    </div>

</div>