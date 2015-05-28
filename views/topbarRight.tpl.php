<?php if ($user !== null): ?>
	<a class = "topbarRightInner" href = "<?= $this->di->url->create("me"); ?>">
		<div id = "topbarRightInnerName">
			<?= $user->name; ?>
		</div>
		
		<div id = "topbarRightInnerRep">
			( <?= ($user->rep ? $user->rep : 0); ?> )
		</div>
		
		<div id = "topbarRightInnerImg">
			<img src = "<?= get_gravatar($user->email, 35); ?>"> 
		</div>
		
	</a>
<?php else: ?>

	<a class = "topbarRightInner" href = "<?= $this->di->url->create("register"); ?>">
		<div class = "centerVertical">
			Register
		</div>
	</a>		
	
	<div class = "linkSeparatorCenter centerVertical right"></div>
	
	<a class = "topbarRightInner" href = "<?= $this->di->url->create($this->di->request->getRoute() . "?login"); ?>">
		<div class = "centerVertical">
			Login
		</div>
	</a>
	
<?php endif; ?>
	
<div class = "clear"></div>