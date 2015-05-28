<?php
	namespace Appelicious\Question;

	class CQuestion extends \Appelicious\MVC\CDatabaseModel
	{	
		private $m_Data;
		private $m_CommentForm;
		private $m_Comments;
		
		public function __construct($p_QuestionData, $p_CommentForm = null, $p_Comments = [])
		{
			$this->m_Data = $p_QuestionData;
			$this->m_CommentForm = $p_CommentForm;
			$this->m_Comments = $p_Comments;
		}
		
		public function getData()
		{
			return $this->m_Data;
		}
		
		public function getCommentForm()
		{
			return $this->m_CommentForm;
		}
		
		public function getComments()
		{
			return $this->m_Comments;
		}		
	}