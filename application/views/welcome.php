<?php
if (isset($user)) :
?>

<h1>Hello <?=$user['name']?> !</h1>
<a class='btn btn-primary btn-large' href='<?=site_url('/tt/'.$user['name'])?>'> Go to your board</a>

<?php
else:
?>

<h1>Hello !</h1>
Please <a href='<?=site_url('login')?>'>log in</a> or <a href='<?=site_url('signup')?>'>sign up</a> to use your Timetracker.

<?php
endif;
?>

