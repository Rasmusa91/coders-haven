<?php
	namespace Appelicious\Form;
	
	class CFormLogin extends CFormExtended
	{
		public function __construct($di)
		{
			$this->setDI($di);
			
			parent::__construct([], [
				'lastRoute' => [
					'value' 		=> $this->di->request->getRoute(),
					'type'        	=> 'hidden',
				],	
				'acronym' => [
					'type'        	=> 'text',
					'label' 		=> "",
					'placeholder'   => 'Acronym',
					'required'    	=> true,
					'validation'  	=> ['not_empty'],
				],
				'password' => [
					'type'        	=> 'password',
					'label' 		=> "",
					'placeholder'   => 'Password',
					'required'    	=> true,
					'validation'  	=> ['not_empty'],
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
			if(!$this->di->User->ValidateLogin([ 	
				"acronym" => $this->di->request->getPost("acronym"),
				"password" => $this->di->request->getPost("password")
			 ]))
			 {
				$output = "Username and password did not match any accounts";
				
				return false;
			 }		
			
			return true;
		}		

		public function callbackSuccess()
		{
			$this->di->User->login($this->di->request->getPost("acronym"));
			
			$url = $this->di->url->create($this->di->request->getPost("lastRoute"));
			$this->di->response->redirect($url);			
		}
		
		public function callbackFail()
		{
			$url = $this->di->url->create($this->di->request->getPost("lastRoute") . "?login");
			$this->di->response->redirect($url);
		}
	}