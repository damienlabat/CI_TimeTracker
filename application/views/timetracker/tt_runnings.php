<div class='row-fluid '>
    <div class='well running clearfix'>

    <div class='span6 '>
    <?php

    if ( count($running['activities'])>0 ):

    ?>

        <h3>Running activities</h3>
        <table class='table'>
        <?php

        foreach($running['activities'] as $activity) {
                echo "<tr>";
                echo "<td><a href='".site_url('tt/'.$user['name'].'/record/'.$activity['id'])."'><i class='icon-flag'></i> ".$activity['activity']['activity_path']."</a></td>";
                echo "<td>".running_time($activity)."</td>";
                echo "<td><a href='".site_url('tt/'.$user['name'].'/record/'.$activity['id'])."/stop' class='btn btn-danger btn-mini'>STOP</a></td>";
                echo "</tr>";
            }

        ?>
        </table>





    <?php else: ?>


    <?php endif; ?>
    <a href='#TODO' class='btn btn-success'>Add new activity</a>
    </div>


    <div class='span5'>
        <h3>Todo</h3>


    <?php

    if ( count($running['todos'])>0 ):

    ?>

        <table class='table'>
    <?php

        foreach($running['todos'] as $todo) {
                echo "<tr>";
                echo "<td><a href='".site_url('tt/'.$user['name'].'/record/'.$todo['id'])."'><i class='icon-exclamation-sign'></i> ".$todo['activity']['activity_path']."</a></td>";
                echo "<td><a href='".site_url('tt/'.$user['name'].'/record/'.$todo['id'])."/stop' class='btn btn-warning btn-mini'>DONE</a></td>";
                echo "</tr>";
                }

    ?>
    </table>
    <?php endif; ?>
    <a href='#TODO' class='btn'>Add new things Todo</a>


    </div>

</div>
</div>