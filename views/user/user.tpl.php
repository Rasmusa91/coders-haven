<h2><?= $subPage; ?> of <b><?= ucfirst($user->acronym); ?></b></h2>

<div id = "profileLeft" class = "left">
	<div id = "profileStatsHolder">
		<div id = "profileStatsImg"> <img src = "<?= get_gravatar($user->email, 200); ?>"> </div>
		<div id = "profileStatsRep"><b><?= ($user->rep ? $user->rep : 0); ?></b> Reputation</div> 
	</div>

	<div id = "profileNav">
		<h3>Navigation</h3>
		<a href = "<?= $this->di->url->create(($myUser && $myUser->id == $user->id ? "me" : "users/id/{$user->id}") . "/activity"); ?>">Activity</a> <br>
		<a href = "<?= $this->di->url->create(($myUser && $myUser->id == $user->id ? "me" : "users/id/{$user->id}") . "/profile"); ?>">Profile</a> <br>
		
		<?php if($myUser && $myUser->id == $user->id): ?>
			<a href = "<?= $this->di->url->create("users/logout"); ?>">Logout</a>
		<?php endif; ?>
	</div>
</div>

<div id = "profileRight" class = "left">
	<?php $this->views->render("profileContent")?>
</div>

<div class = "clear"></div>