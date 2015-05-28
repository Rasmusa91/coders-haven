<?php
	namespace Appelicious\Form;
	
	class CFormRegister extends CFormExtended
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
				'name' => [
					'type'        	=> 'text',
					'label' 		=> "",
					'placeholder'   => 'Full Name',
					'required'    	=> true,
					'validation'  	=> ['not_empty'],
				],
				'email' => [
					'type'        	=> 'email',
					'label' 		=> "",
					'placeholder'   => 'Email',
					'required'    	=> true,
					'validation'  	=> ['not_empty', 'email_adress'],
				],
				'password' => [
					'type'        	=> 'password',
					'label' 		=> "",
					'placeholder'   => 'Password',
					'required'    	=> true,
					'validation'  	=> ['not_empty'],
				],	
				'passwordRe' => [
					'type'        	=> 'password',
					'label' 		=> "",
					'placeholder'   => 'Repeat Password',
					'required'    	=> true,
					'validation'  	=> ['not_empty'],
				],						
				'submitButton' => [
					'value' 		=> 'Submit',
					'type'      	=> 'submit',
					'class' 		=> 'button',
					'callback'  	=> [$this, "callbackSubmit"]
				],
				'resetButton' => [
					'value' 		=> 'Reset',
					'type'      	=> 'reset',
					'class' 		=> 'button'
				]
			]);
		}
		
		public function validate(&$output)
		{
			if(!$this->di->request->getPost("acronym")) 
			{
				$p_Output = "This acronym is already in use";
				
				return false;
			}
			
			if($this->di->request->getPost("password") != $this->di->request->getPost("passwordRe")) 
			{
				$p_Output = "The passwords don't match";
				
				return false;
			}
			
			return true;
		}

		public function callbackSuccess()
		{
			$this->di->User->register([
				"acronym" 	=> $this->di->request->getPost("acronym"),
				"email" 	=> $this->di->request->getPost("email"),
				"name"		=> $this->di->request->getPost("name"),
				"password"	=> $this->di->request->getPost("password")
			]);
			
			$url = $this->di->url->create("me");
			$this->di->response->redirect($url);		
		}
		
		public function callbackFail()
		{
			$url = $this->di->url->create($this->di->request->getPost("lastRoute"));
			$this->di->response->redirect($url);
		}
	}