<?php
	namespace Appelicious\Form;
	
	class CFormQuestionAsk extends CFormExtended
	{
		public function __construct($di)
		{
			$this->setDI($di);
			
			parent::__construct([], [
				'lastRoute' => [
					'value' 		=> $this->di->request->getRoute(),
					'type'        	=> 'hidden',
				],	
				'title' => [
					'type'        	=> 'text',
					'label' 		=> "",
					'placeholder'   => 'Title',
					'required'    	=> true,
					'validation'  	=> ['not_empty'],
				],
				'content' => [
					'type'        	=> 'textarea',
					'label' 		=> "",
					'placeholder'   => 'Question',
					'required'    	=> true,
					'validation'  	=> ['not_empty'],
				],
				'tags' => [
					'type'        	=> 'tags',
					'label' 		=> "Tags ",
					'placeholder'   => 'Tags'
				],				
				'submitButton' => [
					'value' 		=> 'Submit',
					'type'      	=> 'submit',
					'class' 		=> 'button',
					'callback'  	=> [$this, "callbackSubmit"]
				]
			]);
		}
	
		public function validate(&$output)
		{
			
			if(!$this->di->request->getPost("tags") || count(explode(',', $this->di->request->getPost("tags"))) < 3) 
			{
				$output = "Please enter atleast 3 tags";
				return false;
			}
		
			return true;
		}		

		public function callbackSuccess()
		{									
			$id = $this->di->Questions->add([
				'title' 	=> $this->di->request->getPost("title"),
				'parent_id'	=> null,
				'content' 	=> $this->di->request->getPost("content"),
				'author_id'	=> $this->di->User->getUser()->id,
				'type' 		=> 'question',
				'tags'		=> $this->di->request->getPost("tags")
			]);
			
			$url = $this->di->url->create("questions/id/" . $id);
			$this->di->response->redirect($url);			
		}
		
		public function callbackFail()
		{
			$url = $this->di->url->create($this->di->request->getPost("lastRoute"));
			$this->di->response->redirect($url);
		}
	}