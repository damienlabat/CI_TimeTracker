<div class='span6 window todolist'>
        <h1>todo list</h1>
        <?php

    if ( count($running['todos'])>0 ):

    ?>

        <table class='table'>
    <?php

        foreach($running['todos'] as $todo) {
                echo "<tr>";
                echo "<td><a href='".site_url('tt/'.$user['name'].'/todo_'.$todo['id'])."'>".$todo['activity']['activity_path']."</a></td>";
                echo "<td><a href='".site_url('tt/'.$user['name'].'/todo_'.$todo['id'])."/stop' class='btn btn-warning btn-mini'>DONE</a></td>";
                echo "</tr>";
                }

    ?>
    </table>
    <?php else: ?>
    <div>just relax :-)</div><br/>
    <?php endif; ?>
    <a href='<?=site_url('tt/'.$user['name'].'/todo/new')?>' class='btn'>Add new things Todo</a>
    </div>