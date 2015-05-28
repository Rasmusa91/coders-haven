<?php
	namespace Appelicious\Form;
	
	class CFormUserEdit extends CFormExtended
	{
		public function __construct($di, $user)
		{
			$this->setDI($di);
			
			parent::__construct(["id" => "form", "name" => "form"], [
				'lastRoute' => [
					'value' 		=> $this->di->request->getRoute(),
					'type'       	=> 'hidden',
				],	
				'id' => [
					'value' 		=> $user->id,
					'type'        	=> 'hidden',
				],					
				'acronym' => [
					'value' 		=> $user->acronym,
					'type'        	=> 'text',
					'label' 		=> "",
					'placeholder'       => 'Acronym',
					'required'    	=> true,
					'validation'  	=> ['not_empty'],
				],
				'name' => [
					'value' 		=> $user->name,
					'type'        	=> 'text',
					'label' 		=> "",
					'placeholder'   => 'Full Name',
					'required'    	=> true,
					'validation'  	=> ['not_empty'],
				],
				'email' => [
					'value' 		=> $user->email,
					'type'        	=> 'email',
					'label' 		=> "",
					'placeholder'   => 'Email',
					'required'    	=> true,
					'validation'  	=> ['not_empty', 'email_adress'],
				],		

				'password' => [
					'type'        	=> 'password',
					'label' 		=> "",
					'placeholder'   => 'Current password',
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
			],
			true, "submitButton");
		}
		
		public function validate(&$output)
		{
			$user = $this->di->User->getUser();
		
			if(!password_verify($this->di->request->getPost("password"), $user->password)) 
			{
				$output = "Wrong password";
				return false;
			}
			
			if($this->di->request->getPost("acronym") != $user->acronym && !$this->di->User->isUniqueAcronym($user->acronym)) 
			{
				$output = "This acronym is already in use";
				return false;			
			}
						
			return true;
		}

		public function callbackSuccess()
		{
			$this->di->User->edit([
				"id" 		=> $this->di->request->getPost("id"),
				"acronym" 	=> $this->di->request->getPost("acronym"),
				"email" 	=> $this->di->request->getPost("email"),
				"name"		=> $this->di->request->getPost("name")
			]);
			
			$url = $this->di->url->create("me/profile");
			$this->di->response->redirect($url);		
		}
		
		public function callbackFail()
		{
			$url = $this->di->url->create($this->di->request->getPost("lastRoute"));
			$this->di->response->redirect($url);
		}
	}