<h2>Users</h2>

<?php foreach($users as $user): ?>

	<div class = "user">
		<div>
			<div class = "left">
				<img src = "<?= get_gravatar($user->email, 50); ?>">
			</div>
			<div class = "userInfo left">
				<div>
					<a href = "<?= $this->di->url->create("users/id/" . $user->id); ?>"><?= $user->name; ?></a>
				</div>
				<div>
					<?= ($user->rep ? $user->rep : 0); ?>
				</div>
			</div>
		</div>
		
		<div class = "clear"></div>
	</div>

<?php endforeach; ?>