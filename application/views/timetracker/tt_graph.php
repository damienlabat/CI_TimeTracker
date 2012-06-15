<?php
$this->load->view( 'timetracker/form/date_form' );
?>
<div class='span12 ttgraph' data-graph='<?=json_encode($datagraph,JSON_NUMERIC_CHECK)?>'></div>
<button onclick='test()' value='test'>test</button>
<br/>

<label for="groupby_select">Group by</label>
<select id='groupby_select'><?php

 $options=array('hour','day','week');
 foreach( $options as $option) {
    $selected = $option == $current['group_by'] ? ' selected="selected"' : '';
    echo "<option value='".$option."'".$selected.">".$option."</option>";
    }

?></select>
<?php
$this->load->view( 'timetracker/tt_buttons' );