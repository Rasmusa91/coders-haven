<?php
	namespace Appelicious\Form;
	
	class CFormUserChangePassword extends CFormExtended
	{
		public function __construct($di, $user)
		{
			$this->setDI($di);
			
			parent::__construct(["id" => "form2", "name" => "form2"], [
				'lastRoute' => [
					'value' 		=> $this->di->request->getRoute(),
					'type'        	=> 'hidden',
				],	
				'id' => [
					'value' 		=> $user->id,
					'type'        	=> 'hidden',
				],					
				'passwordNew' => [
					'type'        	=> 'password',
					'label' 		=> "",
					'placeholder'   => 'New password',
					'required'    	=> true,
					'validation'  	=> ['not_empty'],
				],
				'passwordRep' => [
					'type'        	=> 'password',
					'label' 		=> "",
					'placeholder'   => 'Repeat password',
					'required'    	=> true,
					'validation'  	=> ['not_empty'],
				],
				'passwordCurr' => [
					'type'        	=> 'password',
					'label' 		=> "",
					'placeholder'   => 'Current password',
					'required'    	=> true,
					'validation'  	=> ['not_empty'],
				],		
				'submitButton2' => [
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
			],
			true, "submitButton2");
		}
				
		public function validate(&$output)
		{
			$user = $this->di->User->getUser();
		
			if($this->di->request->getPost("passwordNew") != $this->di->request->getPost("passwordRep")) 
			{
				$output = "The passwords don't match";
				
				return false;
			}
			
			if(!password_verify($this->di->request->getPost("passwordCurr"), $user->password)) 
			{
				$output = "* Wrong password";
				
				return false;
			}
			
			return true;
		}

		public function callbackSuccess()
		{
			$this->di->User->changePassword([
				"id" 		=> $this->di->request->getPost("id"),
				"password" 	=> $this->di->request->getPost("passwordNew"),
			]);
			
			$url = $this->di->url->create("me/profile");
			$this->di->response->redirect($url);		
		}
		
		public function callbackFail()
		{
			$url = $this->di->url->create("me/edit");
			$this->di->response->redirect($url);
		}
	}