<?php
	namespace Appelicious\Form;
	
	class CFormQuestionAnswer extends CFormExtended
	{
		public function __construct($di, $questionID)
		{
			$this->setDI($di);
			
			parent::__construct(["id" => "answer"], [
				'lastRoute' => [
					'value' 		=> $this->di->request->getRoute(),
					'type'        	=> 'hidden',
				],	
				'questionID' => [
					'value' 		=> $questionID,
					'type'        	=> 'hidden',
				],					
				'content' => [
					'type'        	=> 'textarea',
					'label' 		=> "",
					'placeholder'   => 'Comment',
					'required'    	=> true,
					'validation'  	=> ['not_empty'],
				],				
				'submitButtonAnswer' => [
					'value' 		=> 'Submit',
					'type'      	=> 'submit',
					'class' 		=> 'button',
					'callback'  	=> [$this, "callbackSubmit"]
				]
			],
			true, "submitButtonAnswer");
		}
	
		public function validate(&$output)
		{
			return true;
		}		

		public function callbackSuccess()
		{
			$id = $this->di->Questions->add([
				'title' 	=> null,
				'parent_id'	=> $this->di->request->getPost("questionID"),
				'content' 	=> $this->di->request->getPost("content"),
				'author_id'	=> $this->di->User->getUser()->id,
				'type' 		=> 'answer',
				'tags'		=> null
			]);
			
			$url = $this->di->url->create($this->di->request->getPost("lastRoute"));
			$this->di->response->redirect($url);			
		}
		
		public function callbackFail()
		{
			$url = $this->di->url->create($this->di->request->getPost("lastRoute") . "?login");
			$this->di->response->redirect($url);
		}
	}