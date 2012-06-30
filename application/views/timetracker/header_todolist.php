<div class='window todolist<?php
if ($tt_layout=='tt_home') echo ' span6';
?>'>
        <h1><a href='<?=site_url('tt/'.$user['name'].'/todolist')?>'>Todo list</a></h1>
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
<?php

    else:

?>
    <div>just relax :-)</div><br/>
    <?php endif; ?>
    <a href='<?=site_url('tt/'.$user['name'].'/todo/new')?>' class='btn btn-warning'>Add new things Todo</a>
    </div>