<?php if($myUser && $myUser->id == $user->id): ?>
	<h3>About <a href = "<?= $this->di->url->create("me/edit"); ?>"><i class="fa fa-pencil-square-o"></i></a></h3> 
<?php else: ?>
	<h3>About</h3> 
<?php endif; ?>

<div><?= $user->name; ?></div>
<div><?= $user->email; ?></div>

<div>Member for <b><?= _ago(strtotime($user->created)); ?></b></div>
