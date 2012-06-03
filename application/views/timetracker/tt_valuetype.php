<!--div id='activity' class="span4"><div class='cadre'-->
    <?php
//$this->load->view( 'timetracker/tt_tree' );
?>
<!--/div></div-->

<!--div id='categorie' class="span8"><div class='cadre'-->
    <h2><?= $valuetype[ 'title' ] ?></h2>
<?php
    if ( $valuetype['description']!='' ) echo "<div class='description'>".$valuetype['description']."</div>";
?>
    <a class='btn btn-mini' href='<?=site_url('tt/'.$user_name.'/valuetype/'.$valuetype['id'].'/edit');?>'>edit</a>

<!--/div></div-->

<?php
$this->load->view( 'timetracker/3blocks' );
?>

<div class="btn-toolbar">

    <div class="btn-group">
        <a href='<?php echo viz_url($user_name,'summary','valuetype',$valuetype['id'],'all' ) ?>' class="btn"><i class="icon-list-alt"></i> Summary</a>
    </div>

    <div class="btn-group">
        <a href='<?php echo viz_url($user_name,'graph','valuetype',$valuetype['id'],'all' ) ?>' class="btn"><i class="icon-bar-chart"></i> Graph</a>
    </div>

    <div class="btn-group">
        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-download-alt"></i> Download <span class="caret"></span></a>
        <ul class="dropdown-menu">
            <li><a href='<?php echo viz_url($user_name,'export','valuetype',$valuetype['id'],'all','json' ) ?>' target='_blank'>json</a></li>
            <li><a href='<?php echo viz_url($user_name,'export','valuetype',$valuetype['id'],'all','csv' ) ?>' >csv</a></li>
            <li><a href='<?php echo viz_url($user_name,'export','valuetype',$valuetype['id'],'all','txt' ) ?>' >txt</a></li>
          </ul>
    </div>

</div>