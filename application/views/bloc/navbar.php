 <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">

            <a data-target=".nav-collapse" data-toggle="collapse" class="btn btn-navbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>

            <!--a href="<?=site_url()?>" class="brand">TimeTracker</a-->

            <div class="nav-collapse">

            <ul class="nav">
                <li><a href='<?=site_url()?>'>Home</a></li>
                <?php   if (isset($user_id)) :?>

                <li><a href='<?=site_url('tt/'.$user_name)?>'>Your TimeTracker</a></li>
                <li><a href='<?=site_url('tt/'.$user_name.'/summary')?>'>summary</a></li>
                <li><a href='<?=site_url('tt/'.$user_name.'/graph')?>'>graph</a></li>


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
                            <li><a href='<?=site_url('tt/'.$user_name.'/params')?>'>settings</a></li>
                            <li><a href='<?=site_url('tt/'.$user_name.'/export')?>'>export</a></li>
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
</div>



<!--div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <a data-target=".nav-collapse" data-toggle="collapse" class="btn btn-navbar">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a href="#" class="brand">Project name</a>
          <div class="btn-group pull-right">
            <a href="#" data-toggle="dropdown" class="btn dropdown-toggle">
              <i class="icon-user"></i> Username
              <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
              <li><a href="#">Profile</a></li>
              <li class="divider"></li>
              <li><a href="#">Sign Out</a></li>
            </ul>
          </div>
          <div class="nav-collapse in collapse" style="height: 106px;">
            <ul class="nav">
              <li class="active"><a href="#">Home</a></li>
              <li><a href="#about">About</a></li>
              <li><a href="#contact">Contact</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div-->