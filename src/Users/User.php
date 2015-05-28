<?php
	namespace Appelicious\Users;
 
	/**
	 * Model for Users.
	 *
	 */
	class User extends \Appelicious\MVC\CDatabaseModel
	{	
		private $m_CurrentUser;
		
		public function login($p_Acronym)
		{
			$this->di->session->set("user", $this->findSimpleAcronym($p_Acronym));		
		}		
		
		public function loginID($p_ID)
		{
			$this->di->session->set("user", $this->findSimple($p_ID));	
		}
		
		public function logout()
		{
			$this->di->session->set("user", null);		
		}
		
		public function getUser()
		{
			if(!$this->m_CurrentUser && $this->di->session->get("user")) {
				$this->m_CurrentUser = $this->findSimple($this->di->session->get("user")->id);
			}
			
			return $this->m_CurrentUser;
		}
		
		public function isUniqueAcronym($p_Acronym)
		{
			return empty($this->query()->where("acronym = '{$p_Acronym}'")->executeOne());
		}
		
		public function findSimple($p_ID)
		{
			$query = "
				SELECT user.*, 
					(
						SELECT IFNULL(COUNT(id), 0) FROM question WHERE author_id = user.id
					)
					+
					(SELECT IFNULL(SUM(vote.value), 0) FROM vote WHERE vote.parent_id IN (
						SELECT question.id FROM question WHERE author_id = \"{$p_ID}\"
					) AND vote.type = \"vote\") AS rep
				FROM user
				WHERE user.id = \"{$p_ID}\"
			";

			$this->db->execute($query);
			//dump($this->db->fetchAll());
			return $this->db->fetchOne();
		}
		
		public function findAll($p_Limit = null)
		{
			$limit = "";
			
			if($p_Limit) {
				$limit = " LIMIT " . $p_Limit;
			}
			
			$query = "
				SELECT user.*,
					(SELECT IFNULL(SUM(vote.value), 0) FROM vote WHERE vote.parent_id IN (
						SELECT question.id FROM question WHERE author_id = user.id
					) AND vote.type = \"vote\") + (
						SELECT IFNULL(COUNT(id), 0) FROM question WHERE author_id = user.id
					) AS rep
				FROM user
				ORDER BY rep DESC
				" . $limit . "
			";

			$this->db->execute($query);
			//dump($this->db->fetchAll());
			return $this->db->fetchAll();
		}
		
		public function findSimpleAcronym($p_Acronym)
		{
			$user = $this->query()->where("acronym = '{$p_Acronym}'")->executeOne();
			
			return $this->findSimple($user->id);
		}
		
		public function validateLogin($p_Data = [])
		{
			$user = $this->query()->where("acronym = '{$p_Data["acronym"]}'")->executeOne();
	
			if(!$user || !password_verify($p_Data["password"], $user->password)) 
			{				
				return false;				
			}

			return true;
		}
	
		public function register($p_Data)
		{
			$user = new User();
			$user->setDI($this->di);
		
			$user->save([
				'acronym' 	=> $p_Data["acronym"],
				'email' 	=> $p_Data["email"],
				'name' 		=> $p_Data["name"],
				'password' 	=> password_hash($p_Data["password"], PASSWORD_DEFAULT),
				'created' 	=> gmdate('Y-m-d H:i:s')
			]);
		 
			$this->login($p_Data["acronym"]);
		}		
		
		public function edit($p_Data)
		{
			$users = new User();
			$users->setDI($this->di);		
			$user = $users->find($p_Data["id"]);
			
			$user->acronym 	= $p_Data["acronym"];
			$user->name 	= $p_Data["name"];
			$user->email 	= $p_Data["email"];			
			$user->updated 	= gmdate('Y-m-d H:i:s');

			$user->save();
			
			$this->login($user->acronym);
		}	
		
		public function changePassword($p_Data)
		{
			$user = $this->find($p_Data["id"]);
			
			$user->password = password_hash($p_Data["password"], PASSWORD_DEFAULT);		
			$user->updated = gmdate('Y-m-d H:i:s');
			$user->save();
			
			$this->loginID($user->id);
		}		
		
		public function getHistory($p_ID)
		{			
			$history = [
				"questions"	=> $this->di->Questions->findHistoryByAuthor($p_ID),
				"votes"		=> $this->di->Votes->findHistoryByAuthor($p_ID)
			];
			
			return $history;
		}
	}