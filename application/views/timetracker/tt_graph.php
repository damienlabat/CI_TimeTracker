<script>data=<?=json_encode($records,JSON_NUMERIC_CHECK)?></script>
<pre>data=<?=json_encode($records,JSON_NUMERIC_CHECK)?>

<?php
print_r($current);
?>
</pre>
<?php
$this->load->view( 'timetracker/tt_buttons' );