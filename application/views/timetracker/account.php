<?=get_gravatar($user['email'],80,'mm','g',TRUE,TRUE)?>
<h1><?=$user['name']?></h1>
<h3 class='clearfix'><?=$user['email']?></h3>
<ul>
	<li><a href='<?=site_url('account/email')?>'>change email</a></li>
	<li><a href='<?=site_url('account/password')?>'>change password</a></li>
	<li><a href='<?=site_url('account/unregister')?>'>unregister</a></li>
</ul>

