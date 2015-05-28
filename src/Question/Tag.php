<?php
	namespace Appelicious\Question;

	class Tag extends \Appelicious\MVC\CDatabaseModel
	{	
		public function getBytTitle($p_Title)
		{
			return $this->query()->where("title = '{$p_Title}'")->executeOne();
		}
		
		public function add($p_Data)
		{
			$tag = $this->getBytTitle($p_Data["title"]);

			if(empty($tag))
			{
				$tag = new Tag();
				$tag->setDI($this->di);
			
				$tag->save([
					'title'	=> $p_Data["title"]
				]);
			}
			
			return $tag->id;
		}
		
		public function findPopular($p_Limit = null)
		{
			$limit = "";
			
			if($p_Limit) {
				$limit = "LIMIT " . $p_Limit;
			}
		
			$query = "
				SELECT *,
				(SELECT COUNT(tagconnection.id) FROM tagconnection WHERE tagconnection.tag_id = tag.id) as uses
				 FROM tag
				 ORDER BY uses DESC
				 {$limit}
			";

			$this->db->execute($query);
			//dump($this->db->fetchAll());
			return $this->db->fetchAll();
		}
	}