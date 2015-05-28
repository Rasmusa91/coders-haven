<div id = "home">
	<div id = "tags">
		<h2>Tags</h2>
		
		<?php foreach($tags as $tag): ?>
			<a href = "<?= $this->url->create("questions/tagged/" . $tag->title); ?>" class = "tag left"><?= $tag->title; ?> (<?= $tag->uses; ?>)</a>
		<?php endforeach; ?>
		<div class = "clear"></div>
		<br>
	</div>
	
	<h2>Questions</h2>
	<div id = "questions">
		<?php foreach($questions as $question): ?>
			<?php
				$data = $question;
			?>
			<div class = "question">
				<div class = "sideinfoWrapper">
					<div class = "sideInfo">
						<div class = "value"><?= ($question->votes ? $question->votes : 0); ?></div>
						<div class = "desc">votes</div>
					</div>
					<div class = "sideInfo">
						<div class = "value"><?= ($question->answers ? $question->answers : 0); ?></div>
						<div class = "desc">answers</div>
					</div>
				</div>
				<div class = "content">
					<div class = "title"><a href = "<?= $this->di->url->create("questions/id/" . $data->id); ?>"><?= $data->title; ?></a></div>

					<?php
						$tags = explode(',', $data->tags);
					?>
					<?php if(!empty($data->tags) && count($tags) > 0): ?>
						<div class = "tags left">
							<?php foreach($tags as $tag): ?>
								<a href = "<?= $this->url->create("questions/tagged/" . $tag); ?>" class = "tag left"><?= $tag; ?></a>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
					
					<div class = "lastEvent right">Asked <?= _ago(strtotime($data->created)); ?> ago by <a href = "#"><?= $data->author_name; ?></a></div>
				</div>	
				
				<div class = "clear"></div>
			</div>
		<?php endforeach; ?>
	</div>
	
	<br>

	<h2>Users</h2>
	<div id = "users">

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
	</div>
</div>