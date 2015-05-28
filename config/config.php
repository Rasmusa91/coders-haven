<?php
	define('ANAX_INSTALL_PATH', realpath(__DIR__ . '/../vendor/anax/mvc') . '/');
	define('ANAX_APP_PATH',     ANAX_INSTALL_PATH . 'app/');

	include __DIR__ . "/../vendor/autoload.php";
	include("autoloader.php"); 

	include(ANAX_INSTALL_PATH . 'src/functions.php'); 
	
	require "setup.php"; 