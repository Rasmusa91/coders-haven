<?php
	require "config/config.php"; 
 	
	$di->setShared('db', function() {
		$db = new \Mos\Database\CDatabaseBasic();
		$db->setOptions(require "config/db/database_sqlite.php");
		$db->connect();
		return $db;
	});	
	
	$di->set('UsersController', function() use ($di) {
		$controller = new \Appelicious\Users\UsersController();
		$controller->setDI($di);
		return $controller;
	});	

	$di->set('QuestionsController', function() use ($di) {
		$questions = new \Appelicious\Question\QuestionsController();
		$questions->setDI($di);
		return $questions;
	});	
	
	$di->set("User", function() use ($di) {
		$user = new \Appelicious\Users\User();
		$user->setDI($di);
		return $user;
	});
	
	$di->set("Questions", function() use ($di) {
		$question = new \Appelicious\Question\Question();
		$question->setDI($di);
		return $question;
	});	
	
	$di->set("Tags", function() use ($di) {
		$tag = new \Appelicious\Question\Tag();
		$tag->setDI($di);
		return $tag;
	});	
	
	$di->set("TagConnections", function() use ($di) {
		$tagConnections = new \Appelicious\Question\TagConnection();
		$tagConnections->setDI($di);
		return $tagConnections;
	});	
	
	$di->set("Votes", function() use ($di) {
		$vote = new \Appelicious\Question\Vote();
		$vote->setDI($di);
		return $vote;
	});	
	
	$app->url->setUrlType(\Anax\Url\CUrl::URL_CLEAN);
	$app->views->setBasePath("views");	
	$app->theme->configure("config/theme.php");
	$app->navbar->configure("config/navbar.php");  
	
 	$app->router->add('', function() use ($app) 
	{
		$app->theme->setTitle("Home");
		$app->views->add('home', [
			"tags" => $app->Tags->findPopular(10),
			"questions"	=> $app->Questions->findMultiple(null, 10),
			"users"	=> $app->User->findAll(10)
		]);	
	});  
	
 	$app->router->add('about', function() use ($app) 
	{
		$app->theme->setTitle("About");
		$app->views->add('about');			
	});	
	
 	$app->router->add('register', function() use ($app) 
	{
		$app->theme->setTitle("Register");
		
		$app->dispatcher->forward([
			'controller' => 'users',
			'action'     => 'register'
		]);		
	}); 

 	$app->router->add('me', function() use ($app) 
	{
		$app->dispatcher->forward([
			'controller' => 'users',
			'action'     => 'id',
			'params'	=> ["id" => $app->User->getUser()->id]
		]);	
	}); 	
	
 	$app->router->add('me/profile', function() use ($app) 
	{
		$app->dispatcher->forward([
			'controller' => 'users',
			'action'     => 'id',
			'params'	=> [
				"id" => $app->User->getUser()->id,
				"page" => "profile"
			]
		]);	
	}); 
	
 	$app->router->add('me/activity', function() use ($app) 
	{
		$app->dispatcher->forward([
			'controller' => 'users',
			'action'     => 'id',
			'params'	=> [
				"id" => $app->User->getUser()->id,
				"page" => "activity"
			]
		]);	
	});
	
 	$app->router->add('me/edit', function() use ($app) 
	{
		$app->dispatcher->forward([
			'controller' => 'users',
			'action'     => 'id',
			'params'	=> [
				"id" => $app->User->getUser()->id,
				"page" => "edit"
			]
		]);		
	});	
	
 	$app->router->add('database/reset', function() use ($app) 
	{
		$app->theme->setTitle("Reset Database");
		$app->views->add('databaseReset');
		
		$app->db->dropTableIfExists('user')->execute();
		$app->db->createTable(
			'user',
			[
				'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
				'acronym' 	=> ['varchar(20)', 'unique', 'not null'],
				'email' 	=> ['varchar(80)'],
				'name' 		=> ['varchar(80)'],
				'password'	=> ['varchar(255)'],
				'created' 	=> ['datetime'],
				'updated' 	=> ['datetime'],
				'deleted' 	=> ['datetime']
			]
		)->execute();	
		
		$app->db->dropTableIfExists('question')->execute();
		$app->db->createTable(
			'question',
			[
				'id' 		=> ['integer', 'primary key', 'not null', 'auto_increment'],
				'parent_id'	=> ['integer'],
				'title' 	=> ['varchar(80)'],
				'content' 	=> ['varchar(1000)'],
				'author_id'	=> ['integer'],
				'type' 		=> ['varchar(80)'],
				'created' 	=> ['datetime'],
				'updated' 	=> ['datetime'],
				'deleted' 	=> ['datetime']
			]
		)->execute();	

		$app->db->dropTableIfExists('tag')->execute();
		$app->db->createTable(
			'tag',
			[
				'id' 		=> ['integer', 'primary key', 'not null', 'auto_increment'],
				'title' 	=> ['varchar(80)', 'unique']
			]
		)->execute();		
		
		$app->db->dropTableIfExists('tagconnection')->execute();
		$app->db->createTable(
			'tagconnection',
			[
				'id' 			=> ['integer', 'primary key', 'not null', 'auto_increment'],
				'tag_id'		=> ['integer'],
				'question_id'	=> ['integer'],
			]
		)->execute();	

		$app->db->dropTableIfExists('vote')->execute();
		$app->db->createTable(
			'vote',
			[
				'id' 		=> ['integer', 'primary key', 'not null', 'auto_increment'],
				'parent_id'	=> ['integer'],
				'user_id'	=> ['integer'],
				'type'		=> ['varchar(80)'],
				'value'		=> ['integer'],
				'created' 	=> ['datetime']
			]
		)->execute();		
	}); 

 	$app->router->add('ask', function() use ($app) 
	{
		$app->theme->setTitle("Ask Question");
		$app->dispatcher->forward([
			'controller' => 'questions',
			'action'     => 'ask'
		]);			
	});
	
 	$app->router->add('questions', function() use ($app) 
	{
		$app->theme->setTitle("Questions");
		$app->dispatcher->forward([
			'controller' => 'questions'
		]);			
	});		
	
 	$app->router->add('tagged', function() use ($app) 
	{
		$app->dispatcher->forward([
			'controller'	=> 'questions',
			'action'		=> 'tagged'
		]);			
	});	
	
 	$app->router->add('tags', function() use ($app) 
	{
		$app->theme->setTitle("Tags");
		$app->dispatcher->forward([
			'controller'	=> 'questions',
			'action'		=> 'tags'
		]);			
	});			
	
	if($app->request->getGet("login") !== null) 
	{	
		$app->dispatcher->forward([
			'controller' => 'users',
			'action'     => 'login'
		]);	
	}

	$app->router->handle();	
	$app->theme->render();