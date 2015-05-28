<?php
	// Create services and inject into the app. 
	$di  = new \Anax\DI\CDIFactoryDefault();
	$app = new \Anax\Kernel\CAnax($di);