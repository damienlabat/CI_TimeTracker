<div class='span6 window values'>
        <h1>values</h1>
        <?php

    if ( count($last_values)>0 ):

    ?>
        <h6>last values</h6>
        <table class='table'>
    <?php

        foreach($last_values as $last_values_item) {
                echo "<tr>";
                echo "<td>".$last_values_item['start_time']."</td>";
                echo "<td><a href='".site_url('tt/'.$user['name'].'/value_'.$last_values_item['id'])."'>".$last_values_item['activity']['activity_path']." = ".value( $last_values_item['value'] )."</a></td>";

                echo "</tr>";
                }

    ?>
    </table>
    <a class='pull-right' href='<?=site_url('tt/'.$user['name'].'/values')?>'>view more</a>
    <?php endif; ?>
    <a href='<?=site_url('tt/'.$user['name'].'/value/new')?>' class='btn'>Record new value</a>
</div>