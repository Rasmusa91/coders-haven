<?php
	namespace Appelicious\Users;
 
	/**
	 * A controller for users and admin related events.
	 *
	 */
	class UsersController implements \Anax\DI\IInjectionAware
	{
		use \Anax\DI\TInjectable;
			
		public function indexAction()
		{
			$this->di->theme->setTitle("Users");	
			$this->di->views->add('user/all', 
				[
					"users" => $this->di->User->findAll()
				]
			);
		}

		public function registerAction()
		{
			$form = new \Appelicious\Form\CFormRegister($this->di);
			$form->check();
				
			$this->di->views->add('register', 
				[
					"form" => $form,
				]
			);
		}
		
		public function loginAction()
		{
			$form = new \Appelicious\Form\CFormLogin($this->di);
			$form->check();

			$this->di->views->add('login', 
				[
					"form" => $form,
				],
				"login"
			);			
		}
				
		public function logoutAction()
		{
			$this->di->User->logout();
			$this->di->response->redirect($this->di->url->create(""));			
		}	
	
		public function idAction($id = null, $page = null)
		{
			$user = $this->di->User->findSimple($id);
			$myUser = $this->di->User->getUser();

			$this->di->views->add('user/user', 
				[
					"user" => $user,
					"myUser" => $myUser,
					"subPage" => "Activity"
				]
			);
			
			$page = ($page == null ? "activity" : $page);
			$form = null;
			$form2 = null;
			
			if($page == "edit") 
			{
				$form = new \Appelicious\Form\CFormUserEdit($this->di, $user);
				$form->check();
						
				$form2 = new \Appelicious\Form\CFormUserChangePassword($this->di, $user);
				$form2->check();
			}

			$this->di->views->add('user/' . $page, 
				[
					"user" => $user,
					"myUser" => $myUser,
					"form" => $form,
					"form2" => $form2
				],
				"profileContent"
			);
			
			$this->di->theme->setTitle(($myUser && $user->id == $myUser->id ? "My" : $user->name) . " " . ucfirst($page));	
		}									
	}
	
