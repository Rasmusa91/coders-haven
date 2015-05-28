<div id = "title">
	<?= $title; ?>
</div>

<?php foreach($questions as $question): ?>
	<?php
		$data = $question->getData();
	?>
	
	<div class = "question">
		<div class = "sideinfoWrapper">
			<div class = "sideInfo">
				<div class = "value"><?= ($data->votes ? $data->votes : 0); ?></div>
				<div class = "desc">votes</div>
			</div>
			<div class = "sideInfo">
				<div class = "value"><?= ($data->answers ? $data->answers : 0); ?></div>
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