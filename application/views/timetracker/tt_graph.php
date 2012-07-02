
<div class='ttgraph' data-graph='<?=json_encode($datagraph)?>'></div>


<label for="groupby_select">Group by</label>
<select id='groupby_select'><?php

 $options=array('hour','day','week');
 foreach( $options as $option) {
    $selected = $option == $current['groupby'] ? ' selected="selected"' : '';
    echo "<option value='".$option."'".$selected.">".$option."</option>";
    }

?></select>
<a id='prevbtn' class="btn" href="#"><i class='icon-chevron-left'></i> Prev</a><a id='nextbtn' class="btn" href="#">Next <i class='icon-chevron-right'></i></a>
<?php
