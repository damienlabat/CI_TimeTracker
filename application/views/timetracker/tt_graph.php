<div class='span12 ttgraph' data-graph='<?=json_encode($datagraph,JSON_NUMERIC_CHECK)?>'></div>
<button onclick='test()' value='test'>test</button>
<?php
$this->load->view( 'timetracker/tt_buttons' );