<?php
	namespace Appelicious\Question;
 
	class QuestionsController implements \Anax\DI\IInjectionAware
	{
		use \Anax\DI\TInjectable;
		
		public function indexAction()
		{
			$questions = [];
			$rawQuestions = $this->di->Questions->findMultiple();
					
			foreach($rawQuestions as $rawQuestion)
			{
				$questions[] = new CQuestion($rawQuestion);
			}
			
			$this->di->views->add('question/questions', 
				[
					"title"		=> "Questions",
					"questions" => $questions
				]
			);
		}
		
		public function askAction()
		{
			if($this->di->User->getUser() == null) 
			{
				$this->di->dispatcher->forward([
					'controller' => 'users',
					'action'     => 'login'
				]);
			}

			$this->di->theme->addStylesheet("css/tagit/jquery.tagit.css");
			$this->di->theme->addStylesheet("css/tagit/tagit.ui-zendesk.css");
			$this->di->theme->addJavaScript("js/tagit/tag-it.js");
			$this->di->theme->addJavaScript("js/tags.js");
		
			$form = new \Appelicious\Form\CFormQuestionAsk($this->di);
			$form->check();
			
			$this->di->views->add('question/ask', 
				[
					"form" => $form,
				]
			);
		}
		
		public function idAction($id = null)
		{
			if($id === null) 
			{
				$url = $this->di->url->create("questions");
				$this->di->response->redirect($url);
			}
			
			$data = $this->di->Questions->find($id);
			$commentForm = new \Appelicious\Form\CFormQuestionComment($this->di, $data->id, 0);
			$commentForm->check();
			$comments = $this->di->Questions()->findComments($id);
			$question = new CQuestion($data, $commentForm, $comments);
						
			$answers = [];
			$answersData = $this->di->Questions->findAnswers($id);
			foreach($answersData as $answerData) 
			{
				$commentForm = new \Appelicious\Form\CFormQuestionComment($this->di, $answerData->id, count($answers) + 1);
				$commentForm->check();
				$comments = $this->di->Questions()->findComments($answerData->id);
				
				$answers[] = new CQuestion($answerData, $commentForm, $comments);
			}
			
			$answerForm = new \Appelicious\Form\CFormQuestionAnswer($this->di, $data->id);
			$answerForm->check();
			
			$this->di->theme->setTitle("Question");
			$this->di->views->add('question/question', [
				"user"			=> $this->di->User->getUser(),
				"question" 		=> $question,
				"answerForm" 	=> $answerForm,
				"answers" 		=> $answers
			]);
		}	

		public function tagsAction()
		{
			$this->di->views->add('question/tags', [
				"tags" => $this->di->Tags->findPopular()
			]);			
		}
		
		public function taggedAction($tag = null)
		{
			if($tag === null) 
			{
				$url = $this->di->url->create("questions");
				$this->di->response->redirect($url);
			}			
		
			$this->di->theme->setTitle("Tagged Questions");
			
			$questions = [];
			$rawQuestions = $this->di->Questions->findMultiple($tag);
					
			foreach($rawQuestions as $rawQuestion)
			{
				$questions[] = new CQuestion($rawQuestion);
			}
			
			$this->di->views->add('question/questions', 
				[
					"title"		=> "Questions with the tag <b>{$tag}</b>",
					"questions" => $questions
				]
			);		
		}		
		
		public function upvoteAction()
		{
			$this->vote("vote", 1);
		}
		
		public function downvoteAction()
		{
			$this->vote("vote", -1);
		}

		public function answerAction()
		{
			$this->vote("answer", 5);
		}		
		
		private function vote($p_Type, $p_Value)
		{
			$id = $this->di->request->getPost("parent_id");
			$user = $this->di->User->getUser();
			
			if($id === null) {
				$url = $this->di->url->create("");
				$this->di->response->redirect($url);
			}
			
			$this->di->Votes->add(
				[
					"parent_id" => $id,
					"user_id"	=> $user->id,
					"type"		=> $p_Type,
					"value"		=> $p_Value
				]
			);
			
			$url = $this->di->url->create($this->di->request->getPost("lastRoute"));
			$this->di->response->redirect($url);			
		}
	}