<?php
    $this->load->view( 'timetracker/header_activities' );
    $this->load->view( 'timetracker/tt_menu' );
    $this->load->view( 'timetracker/tab_buttons' );
?>
<div class='ttgraph' data-graph='<?=json_encode($datagraph)?>'></div>


<label for="groupby_select">Group by</label>
<select id='groupby_select'><?php

 $options=array('hour','day','week');
 foreach( $options as $option) {
    $selected = $option == $current['groupby'] ? ' selected="selected"' : '';
    echo "<option value='".$option."'".$selected.">".$option."</option>";
    }

?></select>
<?php
