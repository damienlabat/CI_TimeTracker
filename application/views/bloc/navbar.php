 <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">

            <!--a href="<?=site_url()?>" class="brand">TimeTracker</a-->

            <ul class="nav">
                <li><a href='<?=site_url()?>'>Home</a></li>
                <?php   if (isset($user_id)) :?>

                <li><a href='<?=site_url('tt/'.$user_name)?>'>Your TimeTracker</a></li>

                <?php else: ?>

                <li><a href='<?=site_url('login')?>'>Log in</a></li>
                <li><a href='<?=site_url('signup')?>'>Sign up</a></li>

                <?php endif ?>
            </ul>


            <ul class="nav pull-right">
            <?php   if (isset($user_id)) :?>

                    <ul class="nav">
                        <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?=$user_name?><b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href='<?=site_url('account')?>'>account</a></li>
                            <li><a href='<?=site_url('tt/'.$user_name.'/settings')?>'>settings</a></li>
                        </ul>
                        </li>
                    </ul>

                    <li><a href='<?=site_url('help')?>'>Help</a></li>
                    <li><a href='<?=site_url('logout')?>'>Log Out</a></li>

            <?php else: ?>

            <?php endif ?>
            </ul>
        </div>
      </div>
</div>