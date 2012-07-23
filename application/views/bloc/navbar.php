
<?php


if (isset($user)) : /* PRIVATE NAVBAR */


?><div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">

            <a data-target=".nav-collapse" data-toggle="collapse" class="btn btn-navbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>

            <a href="<?php
                if ( !isset($tt_layout) OR ($tt_layout!='tt_home')) echo site_url('tt/'.$user['name']);
                    else echo site_url();
                ?>" class="brand current_time"><?=date('G:i:s')?></a>

            <div class="nav-collapse">


                <ul class="nav">
                    <li>
                        <a href="<?=site_url('tt/'.$user['name'].'/activities')?>"<?php
                        if (count($running['activities'])>0) {
                                $text='';
                                foreach( $running['activities'] as $record ) $text.='<li><strong>'.$record['activity']['activity_path'].'</strong> '.running_time($record).'</li>';
                                echo ' data-content=\'<ul>'.$text.'</ul>\' rel="popover" class="popme" data-original-title="running"';
                                }
                        ?>>Activities<?php
                        if (count($running['activities'])>0) echo ' <span class="badge badge-info">'.count($running['activities']).'</span>';
                        ?></a>
                    </li>
                    <li>
                        <a href="<?=site_url('tt/'.$user['name'].'/todolist')?>"<?php
                        if (count($running['todos'])>0) {
                                $text='';
                                foreach( $running['todos'] as $record ) $text.='<li><strong>'.$record['activity']['activity_path'].'</strong></li>';
                                echo ' data-content=\'<ul>'.$text.'</ul>\' rel="popover" class="popme" data-original-title="todo list"';
                                }
                        ?>>Todo list<?php
                        if (count($running['todos'])>0) echo ' <span class="badge badge-warning">'.count($running['todos']).'</span>';
                        ?></a>
                    </li>
                    <li>
                        <a href="<?=site_url('tt/'.$user['name'].'/values')?>">Values</a>
                    </li>
                </ul>


                <ul class="nav pull-right">
                    <ul class="nav">
                        <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" id='user_logged' data-user-id='<?$user['id']?>'><?=get_gravatar($user['email'])?> <?=$user['name']?> <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href='<?=site_url('account')?>'>Account</a></li>
                            <li><a href='<?=site_url('tt/'.$user['name'].'/settings')?>'>Settings</a></li>
                            <li><a href='<?=site_url('tt/'.$user['name'].'/friends')?>'>Friends</a></li>
                        </ul>
                        </li>
                    </ul>

                    <li><a href='<?=site_url('help')?>'>Help</a></li>
                    <li><a href='<?=site_url('logout')?>'>Log Out</a></li>
                </ul>

            </div>
        </div>
      </div>
</div>
<?php





else : /* PUBLIC NAVBAR */





?>
 <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">

            <a data-target=".nav-collapse" data-toggle="collapse" class="btn btn-navbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>

            <a href="<?=site_url()?>" class="brand">TimeTracker</a>

            <div class="nav-collapse">


                <ul class="nav pull-right">
                    <li><a href='<?=site_url('login')?>'>Log in</a></li>
                    <li><a href='<?=site_url('signup')?>'>Sign up</a></li>
                </ul>

            </div>
        </div>
      </div>
</div>
<?php



endif;
