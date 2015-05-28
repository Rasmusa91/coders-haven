<?php
	$data = $this->di->User->getHistory($user->id);
	$questions = $data["questions"];
	$votes = $data["votes"];

	$history = [];
	
	if($questions != null)
	{
		foreach($questions as $question) 
		{
			if($question->type == "question") 
			{
				$history[] = [
					"title"		=> "<a href = \"" . $this->di->url->create("questions/id/" . $question->id). "\">question</a>",
					"content"	=> "Asked a question",
					"date"		=> $question->created
				];
			}
			else if($question->type == "answer") 
			{
				$history[] = [
					"title"		=> "<a href = \"" . $this->di->url->create("questions/id/" . $question->parent_id). "\">question</a>",
					"content"	=> "Answered a question",
					"date"		=> $question->created
				];
			}
			else if($question->type == "comment") 
			{
				if($question->parent_parent_id) 
				{
					$history[] = [
						"title"		=> "<a href = \"" . $this->di->url->create("questions/id/" . $question->parent_parent_id). "\">question</a>",
						"content"	=> "Commented on an answer",
					"date"		=> $question->created
					];
				}
				else 
				{
					$history[] = [
						"title"		=> "<a href = \"" . $this->di->url->create("questions/id/" . $question->parent_id). "\">question</a>",
						"content"	=> "Commented on a question",
					"date"		=> $question->created
					];		
				}
			}
		}
	}
	foreach($votes as $vote) 
	{
		if($vote->type == "answer") 
		{
			$history[] = [
				"title"		=> "<a href = \"" . $this->di->url->create("questions/id/" . $vote->parent_parent_id). "\">question</a>",
				"content"	=> "Accepted an answer",
				"date"		=> $vote->created
			];
		}
		else if($vote->type == "vote") 
		{
			$history[] = [
				"title"		=> "<a href = \"" . $this->di->url->create("questions/id/" . $vote->parent_parent_id). "\">question</a>",
				"content"	=> "Voted on " . ($vote->parent_type == "answer" ? "an" : "a") . " {$vote->parent_type}",
				"date"		=> $vote->created
			];			
		}
	}
	
	mergesort($history, function ($a, $b) {
		$sa = $a["date"];
		$sb = $b["date"];

		if ($sa == $sb) {
			return 0;
		}

		return $sa > $sb ? -1 : 1;
	});
?>

<?php foreach($history as $event): ?>
	<div><?= $event["title"]; ?></div>
	<div><?= $event["content"]; ?></div>
	<div><?= _ago(strtotime($event["date"])); ?> ago</div>
<?php endforeach; ?>