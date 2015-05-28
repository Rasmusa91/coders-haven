<?php
	namespace Appelicious\Question;

	class Vote extends \Appelicious\MVC\CDatabaseModel
	{	
		public function findVote($p_ParentID, $p_UserID, $p_Type)
		{
			return $this->query()->where("parent_id = '{$p_ParentID}'")->andWhere("user_id = '{$p_UserID}'")->andWhere("type = '{$p_Type}'")->executeOne();
		}
	
		public function add($p_Data)
		{
			$vote = $this->findVote($p_Data["parent_id"], $p_Data["user_id"], $p_Data["type"]);

			if(empty($vote))
			{
				if($p_Data["type"] == "answer") 
				{
					$query = "
						DELETE FROM vote WHERE parent_id IN (
							SELECT id FROM question WHERE parent_id IN (
								SELECT parent_id FROM question WHERE id = \"{$p_Data["parent_id"]}\"
						)) AND type = \"answer\";
					";

					$this->db->execute($query);				
				}

				$vote = new Vote();
				$vote->setDI($this->di);
			
				$vote->save([
					'parent_id'	=> $p_Data["parent_id"],
					'user_id'	=> $p_Data["user_id"],
					'type' 		=> $p_Data["type"],
					'value' 	=> $p_Data["value"],
					'created' 	=> gmdate('Y-m-d H:i:s')
				]);
			}
			else 
			{
				$vote = $this->find($vote->id);
				
				if($vote->type == "answer") 
				{
					$this->delete($vote->id);
				}
				else if($vote->type == "vote") 
				{
					if($p_Data["value"] == $vote->value) {
						$this->delete($vote->id);
					}
					else 
					{
						$vote->value *= -1;
						$vote->save();
					}
				}
			}
		}
		
		public function findHistoryByAuthor($p_AuthorID)
		{
			$query = "
				SELECT v1.*,
				(SELECT parent_id FROM question WHERE id = v1.parent_id) as parent_parent_id,
				(SELECT type FROM question WHERE id = v1.parent_id) as parent_type
				FROM vote as v1
				WHERE v1.user_id = \"{$p_AuthorID}\";
			";

			$this->db->execute($query);
			//dump($this->db->fetchAll());
			return $this->db->fetchAll();
		}		
	}
	