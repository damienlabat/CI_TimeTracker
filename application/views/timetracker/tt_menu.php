<div class='well'>
    <h6>Records between the <?=$current['datefrom']?> and the <?=$current['dateto']?></h6>
    <?php
        $this->load->view( 'timetracker/form/date_form' );
?>
</div>