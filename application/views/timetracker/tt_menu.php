<div class="well span6">
    <ul class="nav nav-list">
        <!--li class="nav-header">! add title current select</li-->
<?php

    $list_action=array(
        array( 'action'=>'record',  'title'=>'Records', 'icon'=>'icon-list-alt'),
        array( 'action'=>'summary', 'title'=>'Summary', 'icon'=>'icon-book'),
        array( 'action'=>'graph',   'title'=>'Graph',   'icon'=>'icon-signal')
    );

    foreach ($list_action as $a) {
        if ($a['action']==$current['action'])
                echo '<li class="active"><a href="'.tt_url($user['name'],$a['action'],$current ).'"><i class="icon-white '.$a['icon'].'"></i> '.$a['title'].'</a></li>';
            else
                echo '<li><a href="'.tt_url($user['name'],$a['action'],$current ).'"><i class="'.$a['icon'].'"></i> '.$a['title'].'</a></li>';
        }


?>

        <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="dropdown-toggle"><i class="icon-download-alt"></i> Download <b class="caret"></b></a>
            <ul class="dropdown-menu" id="menu1">
                <li><a href="<?php echo tt_url($user['name'],'export',$current, array('format'=>'json') ) ?>">csv</a></li>
                <li><a href="<?php echo tt_url($user['name'],'export',$current, array('format'=>'json') ) ?>">txt</a></li>
                <li><a href="<?php echo tt_url($user['name'],'export',$current, array('format'=>'json') ) ?>" target='_blank'>json</a></li>
             </ul>
        </li>


        <li class="divider"></li>
        <li><a href="<?=site_url($user['name'].'/settings#'.$current['action'])?>"><i class="icon-cog"></i> Settings</a></li>
        <li><a href="<?=site_url('help#'.$current['action'])?>"><i class="icon-flag"></i> Help</a></li>
    </ul>
</div>

</nav>