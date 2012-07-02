<?php
    $this->load->view( 'timetracker/header_activities' );
    $this->load->view( 'timetracker/tt_menu' );
    $this->load->view( 'timetracker/records_list' );
?>
<div class="btn-group pull-right">
            <a href="#" data-toggle="dropdown" class="btn dropdown-toggle"><i class="icon-download-alt"></i> Download <b class="caret"></b></a>
            <ul class="dropdown-menu" id="menu1">
                <li><a href="<?=tt_url($user['name'],'export',$current, array('format'=>'csv') )?>">csv</a></li>
                <li><a href="<?=tt_url($user['name'],'export',$current, array('format'=>'txt') )?>">txt</a></li>
                <li><a href="<?=tt_url($user['name'],'export',$current, array('format'=>'json') )?>" target='_blank'>json</a></li>
             </ul>
</div>