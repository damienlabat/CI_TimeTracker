<div class='span12 ttgraph' data-graph='<?=json_encode($datagraph,JSON_NUMERIC_CHECK)?>'></div>
<pre>
<?php
print_r($records);
?>
</pre>
<pre>
<?php
print_r($current);
?>
</pre>
<?php
$this->load->view( 'timetracker/tt_buttons' );