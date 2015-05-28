<script>
	function upvote(p_ID)
	{
		var form = document.getElementById("form-" + p_ID);
		form.action = "<?= $this->di->url->create("questions/upvote"); ?>";
		form.submit();
	}
	
	function downvote(p_ID)
	{
		var form = document.getElementById("form-" + p_ID);
		form.action = "<?= $this->di->url->create("questions/downvote"); ?>";
		form.submit();
	}
	
	function answer(p_ID)
	{
		var form = document.getElementById("form-" + p_ID);
		form.action = "<?= $this->di->url->create("questions/answer"); ?>";
		form.submit();
	}
	
	function showCommentForm(p_ID)
	{
		document.getElementById("addComment-" + p_ID).className = "hidden";
		document.getElementById("addCommentForm-" + p_ID).className = "commentAdd";
	}
</script>

<?php
	$data = $question->getData();
	$questionAuthor = $data->author_id;
	$author = $this->di->User->findSimple($data->author_id);
?>

<h2><?= $data->title; ?></h2>

<div class = "singleQuestion">
	<div class = "vote">
		<form method = "post" id = "form-<?= $data->id; ?>">
			<input name = "parent_id" value = "<?= $data->id; ?>" type = "hidden">
			<input name = "lastRoute" value = "<?= $this->di->request->getRoute(); ?>" type = "hidden">
			<div><a onclick = "upvote(<?= $data->id; ?>); return false;"><i class="<?= ($data->user_vote == 1 ? "userVote" : ""); ?> fa fa-caret-up fa-3x"></i></a></div>
			<div class = "score"><?= ($data->votes ? $data->votes : 0); ?></div>
			<div><a onclick = "downvote(<?= $data->id; ?>); return false;"><i class="<?= ($data->user_vote == -1 ? "userVote" : ""); ?> fa fa-caret-down fa-3x"></i></a></div>
		</form>		
	</div>
	<div class = "content">
		<div class = "message">
			<?= $this->di->textFilter->doFilter($data->content, 'shortcode, markdown'); ?>
		</div>
		<?php
			$tags = explode(',', $data->tags);
		?>
		<?php if(!empty($data->tags) && count($tags) > 0): ?>
			<div class = "tags">
				<?php foreach($tags as $tag): ?>
					<a href = "<?= $this->url->create("questions/tagged/" . $tag); ?>" class = "tag left"><?= $tag; ?></a>
				<?php endforeach; ?>
				<div class = "clear"></div>
			</div>
		<?php endif; ?>
		
		<div class = "author right">
			<div class = "desc">asked <?= _ago(strtotime($data->created)); ?> ago</div>
			<div>
				<div class = "left">
					<img src = "<?= get_gravatar($author->name, 50); ?>">
				</div>
				<div class = "authorInfo left">
					<div>
						<a href = "#"><?= $author->name; ?></a>
					</div>
					<div>
						<?= ($author->rep ? $author->rep : 0); ?>
					</div>
				</div>
			</div>
		</div>
		<div class = "clear"></div>

		<div class = "comments">
			<?php foreach($question->getComments() as $comment): ?>
				<div class = "comment">
					<div class = "vote">
						<form method = "post" id = "form-<?= $comment->id; ?>">
							<input name = "parent_id" value = "<?= $comment->id; ?>" type = "hidden">
							<input name = "lastRoute" value = "<?= $this->di->request->getRoute(); ?>" type = "hidden">
							<div><a onclick = "upvote(<?= $comment->id; ?>); return false;"><i class="<?= ($comment->user_vote == 1 ? "userVote" : ""); ?> fa fa-caret-up fa-1x"></i></a></div>
							<div class = "score"><?= ($comment->votes ? $comment->votes : 0); ?></div>
							<div><a onclick = "downvote(<?= $comment->id; ?>); return false;"><i class="<?= ($comment->user_vote == -1 ? "userVote" : ""); ?> fa fa-caret-down fa-1x"></i></a></div>
						</form>
					</div>
					<div class = "message">
						<div>
							<?= $this->di->textFilter->doFilter($comment->content, 'shortcode, markdown'); ?>
						</div>
						<div class = "author right">
							by <a href = "#"><?= $comment->author_name; ?></a> <?= _ago(strtotime($comment->created)); ?> ago
						</div>
						<div class = "clear"></div>
					</div>
					<div class = "clear"></div>
				</div>
			<?php endforeach; ?>
			<div class = "addComment" id = "addComment-<?= $data->id; ?>">
				<a onclick = "showCommentForm(<?= $data->id; ?>); return false;">Add a comment</a>
			</div>
			<div class = "commentAdd hidden" id = "addCommentForm-<?= $data->id; ?>">
				<?= $question->getCommentForm()->render(); ?>
			</div>			
		</div>
	</div>
</div>

<h3><?= count($answers); ?> Answers</h3>

<?php foreach($answers as $answer): ?>
<?php
	$data = $answer->getData();
	$author = $this->di->User->findSimple($data->author_id);
?>	

	<div class = "singleQuestion answer">
		<div class = "vote">
			<form method = "post" id = "form-<?= $data->id; ?>">
				<input name = "parent_id" value = "<?= $data->id; ?>" type = "hidden">
				<input name = "lastRoute" value = "<?= $this->di->request->getRoute(); ?>" type = "hidden">
				<div><a onclick = "upvote(<?= $data->id; ?>); return false;"><i class="<?= ($data->user_vote == 1 ? "userVote" : ""); ?> fa fa-caret-up fa-3x"></i></a></div>
				<div class = "score"><?= ($data->votes ? $data->votes : 0); ?></div>
				<div><a onclick = "downvote(<?= $data->id; ?>); return false;"><i class="<?= ($data->user_vote == -1 ? "userVote" : ""); ?> fa fa-caret-down fa-3x"></i></a></div>
				
				<?php if($user->id == $questionAuthor): ?>
					<div><a onclick = "answer(<?= $data->id; ?>); return false;"><i class="<?= ($data->answered == 1 ? "isAnswer" : ""); ?> fa fa-check fa-2x"></i></a></div>
				<?php elseif($data->answered == 1): ?>
					<div><i class="<?= ($data->answered == 1 ? "isAnswer" : ""); ?> fa fa-check fa-2x"></i></div>
				<?php endif; ?>
			</form>
		</div>
		<div class = "content">
			<div class = "message">
				<?= $this->di->textFilter->doFilter($data->content, 'shortcode, markdown'); ?>
			</div>
			
			<div class = "author right">
				<div class = "desc">answered <?= _ago(strtotime($data->created)); ?> ago</div>
				<div>
					<div class = "left">
						<img src = "<?= get_gravatar($author->email, 50); ?>">
					</div>
					<div class = "authorInfo left">
						<div>
							<a href = "#"><?= $author->name; ?></a>
						</div>
						<div>
							<?= ($author->rep ? $author->rep : 0); ?>
						</div>
					</div>
				</div>
			</div>
			<div class = "clear"></div>

			<div class = "comments">
				<?php foreach($answer->getComments() as $comment): ?>
					<div class = "comment">
						<div class = "vote">
							<form method = "post" id = "form-<?= $comment->id; ?>">
								<input name = "parent_id" value = "<?= $comment->id; ?>" type = "hidden">
								<input name = "lastRoute" value = "<?= $this->di->request->getRoute(); ?>" type = "hidden">

								<div><a onclick = "upvote(<?= $comment->id; ?>); return false;"><i class="<?= ($comment->user_vote == 1 ? "userVote" : ""); ?> fa fa-caret-up fa-1x"></i></a></div>
								<div class = "score"><?= ($comment->votes ? $comment->votes : 0); ?></div>
								<div><a onclick = "downvote(<?= $comment->id; ?>); return false;"><i class="<?= ($comment->user_vote == -1 ? "userVote" : ""); ?> fa fa-caret-down fa-1x"></i></a></div>
							</form>
						</div>
						<div class = "message">
							<div>
								<?= $this->di->textFilter->doFilter($comment->content, 'shortcode, markdown'); ?>
							</div>
							<div class = "author right">
								by <a href = "#"><?= $comment->author_name; ?></a> <?= _ago(strtotime($comment->created)); ?> ago
							</div>
							<div class = "clear"></div>
						</div>
						<div class = "clear"></div>
					</div>
				<?php endforeach; ?>
				<div class = "addComment" id = "addComment-<?= $data->id; ?>">
					<a onclick = "showCommentForm(<?= $data->id; ?>); return false;">Add a comment</a>
				</div>
				<div class = "commentAdd hidden" id = "addCommentForm-<?= $data->id; ?>">
					<?= $answer->getCommentForm()->render(); ?>
				</div>					
			</div>
		</div>
		<div class = "clear"></div>
	</div>
<?php endforeach; ?>

<h3>Answer Question</h3>
<div id = "answerForm">
	<?= $answerForm->render(); ?>
</div>