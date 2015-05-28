<?php
	namespace Appelicious\Question;

	class TagConnection extends \Appelicious\MVC\CDatabaseModel
	{	
		public function add($p_Data)
		{
			$tagConnection = new TagConnection();
			$tagConnection->setDI($this->di);
		
			$tagConnection->save([
				'tag_id'	=> $p_Data["tag_id"],
				'question_id' 	=> $p_Data["question_id"]
			]);
			
			return $tagConnection->id;
		}
	}