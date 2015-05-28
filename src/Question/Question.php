<?php
	namespace Appelicious\Question;

	class Question extends \Appelicious\MVC\CDatabaseModel
	{	
		public function add($p_Data)
		{
			$question = new Question();
			$question->setDI($this->di);
		
			$question->save([
				'parent_id'	=> $p_Data["parent_id"],
				'title' 	=> $p_Data["title"],
				'content' 	=> $p_Data["content"],
				'author_id'	=> $p_Data["author_id"],
				'type' 		=> $p_Data["type"],
				'created' 	=> gmdate('Y-m-d H:i:s')
			]);
			
			if($p_Data["tags"] !== null) {
				$this->addTags($p_Data["tags"], $question->id);
			}
			
			return $question->id;
		}
		
		public function addTags($p_Tags, $p_QuestionID)
		{
			$tags = explode(',', $p_Tags);
			
			foreach($tags as $tag) {
				$tagsID = $this->di->Tags->add([
					"title" => $tag
				]);
				
				$this->di->TagConnections->add([
					"tag_id" 		=> $tagsID,
					"question_id" 	=> $p_QuestionID
				]);
			}
		}
		
		public function findMultiple($tag = null, $p_Limit = null)
		{	
			$limit = "";
			
			if($p_Limit) {
				$limit = " LIMIT " . $p_Limit;
			}
			
			$query = "
				SELECT question.*, user.name as author_name,
				(SELECT GROUP_CONCAT(tag.title) FROM tagconnection, tag WHERE tagconnection.question_id = question.id AND tagconnection.tag_id = tag.id) as tags,
				(SELECT SUM(vote.value) FROM vote WHERE vote.parent_id = question.id) as votes,
				(SELECT COUNT(q2.id) FROM question AS q2 WHERE q2.parent_id = question.id AND q2.type = \"answer\") as answers
				FROM question
				INNER JOIN user
				ON user.id = question.author_id
				WHERE question.type = \"question\" " . ($tag == null ? "" : "AND tags LIKE \"%{$tag}%\"") . "
				ORDER BY question.created DESC
				" . $limit . "
			";

			$this->db->execute($query);
			//dump($this->db->fetchAll());
			return $this->db->fetchAll();
		}
		
		public function find($id)
		{
			$userID = "";
			$user = $this->di->User->getUser();
			if($user) {
				$userID = $user->id;
			}
			
			$query = "
				SELECT question.*, user.name as author_name,
				(SELECT GROUP_CONCAT(tag.title) FROM tagconnection, tag WHERE tagconnection.question_id = question.id AND tagconnection.tag_id = tag.id) as tags,
				(SELECT SUM(vote.value) FROM vote WHERE vote.parent_id = question.id AND vote.type = \"vote\") as votes,
				(SELECT SUM(vote.value) FROM vote WHERE vote.parent_id = question.id AND vote.type = \"vote\" AND vote.user_id = \"{$userID}\") as user_vote
				FROM question
				INNER JOIN user
				ON user.id = question.author_id
				WHERE question.type = \"question\" AND question.id = \"{$id}\"
			";

			$this->db->execute($query);
			//dump($this->db->fetchAll());
			return $this->db->fetchOne();
		}
		
		public function findAnswers($id)
		{
			$userID = "";
			$user = $this->di->User->getUser();
			if($user) {
				$userID = $user->id;
			}
			
			$query = "
				SELECT question.*, user.name as author_name,
				(SELECT SUM(vote.value) FROM vote WHERE vote.parent_id = question.id AND vote.type = \"vote\") as votes,
				(SELECT SUM(vote.value) FROM vote WHERE vote.parent_id = question.id AND vote.type = \"vote\" AND vote.user_id = \"{$userID}\") as user_vote,
				(SELECT 1 FROM vote WHERE vote.parent_id = question.id AND vote.type = \"answer\") answered
				FROM question
				INNER JOIN user
				ON user.id = question.author_id
				WHERE question.type = \"answer\" AND parent_id = \"{$id}\"
			";

			$this->db->execute($query);
			//dump($this->db->fetchAll());
			return $this->db->fetchAll();
		}		
		
		public function findComments($id)
		{
			$userID = "";
			$user = $this->di->User->getUser();
			if($user) {
				$userID = $user->id;
			}
			
			$query = "
				SELECT question.*, user.name as author_name,
				(SELECT SUM(vote.value) FROM vote WHERE vote.parent_id = question.id AND vote.type = \"vote\") as votes,
				(SELECT SUM(vote.value) FROM vote WHERE vote.parent_id = question.id AND vote.type = \"vote\" AND vote.user_id = \"{$userID}\") as user_vote
				FROM question
				INNER JOIN user
				ON user.id = question.author_id
				WHERE question.type = \"comment\" AND parent_id = \"{$id}\"
			";

			$this->db->execute($query);
			//dump($this->db->fetchAll());
			return $this->db->fetchAll();
		}
		
		public function findHistoryByAuthor($p_AuthorID)
		{
			$query = "
				SELECT q1.*,
				(SELECT parent_id FROM question WHERE id = q1.parent_id) as parent_parent_id
				FROM question as q1
				WHERE q1.author_id = \"{$p_AuthorID}\";
			";

			$this->db->execute($query);
			//dump($this->db->fetchAll());
			return $this->db->fetchAll();
		}
	}