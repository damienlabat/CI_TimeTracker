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

            <?php   if (isset($user)) : /* PRIVATE NAVBAR */ ?>

            <ul class="nav">
            <?php $this->load->view('timetracker/tt_navbar_button'); ?>
            </ul>




                <ul class="nav pull-right">
                    <ul class="nav">
                        <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?=$user['name']?> <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href='<?=site_url('account')?>'>account</a></li>
                            <li><a href='<?=site_url('tt/'.$user['name'].'/settings')?>'>settings</a></li>
                        </ul>
                        </li>
                    </ul>

                    <li><a href='<?=site_url('help')?>'>Help</a></li>
                    <li><a href='<?=site_url('logout')?>'>Log Out</a></li>
                </ul>


            <?php else : /* PUBLIC NAVBAR */ ?>


                <ul class="nav pull-right">
                    <li><a href='<?=site_url('login')?>'>Log in</a></li>
                    <li><a href='<?=site_url('signup')?>'>Sign up</a></li>
                </ul>



            <?php endif ?>

            </div>
        </div>
      </div>
</div>


