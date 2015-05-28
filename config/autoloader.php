<?php
/**
 * Enable autoloaders.
 *
 */


/**
 * Default Anax autoloader, and the add specifics through a self invoking anonomous function.
 * Add autoloader for namespace Anax and a default directory for unknown vendor namespaces.
 */
require ANAX_INSTALL_PATH . 'src/Loader/CPsr4Autoloader.php';

call_user_func(function() {
    $loader = new \Anax\Loader\CPsr4Autoloader();
    $loader->addNameSpace('Appelicious', __DIR__ . "/../" . 'src')
			->addNameSpace('Michelf', ANAX_INSTALL_PATH . '3pp/php-markdown/Michelf')
			->register();
});