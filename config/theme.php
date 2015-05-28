<?php
/**
 * Config-file for Anax, theme related settings, return it all as array.
 *
 */
 
$views = [
	 [
		'region' => 'topbarRight', 
		'template' => 'topbarRight', 
		'data' => [
			"user" => $this->di->User->getUser()
		], 
		'sort' => -1
	],
	
	 [
		'region' => 'topbarLeft', 
		'template' => 'topbarLeft', 
		'data' => [], 
		'sort' => -1
	],		

	[
		'region' => 'header', 
		'template' => 'header', 
		'data' => [], 
		'sort' => -1
	],
	
	[
		'region' => 'navbar', 
		'template' => [
			'callback' => function() {
				return $this->di->navbar->create();
			},
		], 
		
		'data' => [], 
		'sort' => -1
	],
	
	[
		'region' => 'preFooter1', 
		'template' => 'preFooter1', 
		'data' => [], 
		'sort' => -1
	],
	
	[
		'region' => 'preFooter2', 
		'template' => 'preFooter2', 
		'data' => [], 
		'sort' => -1
	],

	[
		'region' => 'preFooter3', 
		'template' => 'preFooter3', 
		'data' => [], 
		'sort' => -1
	],

	[
		'region' => 'preFooter4', 
		'template' => 'preFooter4', 
		'data' => [], 
		'sort' => -1
	],

	[
		'region' => 'footerRight', 
		'template' => 'footerRight', 
		'data' => [], 
		'sort' => -1
	],

	[
		'region' => 'footerLeft', 
		'template' => 'footerLeft', 
		'data' => [], 
		'sort' => -1
	]
];
 
if($this->di->has("flashSession") && $this->di->flashSession->hasMessages())
{
	$views[] = 	[
			'region'   => 'flash', 
			'template' => [
				'callback' => function() {
					$flash = $this->di->flashSession->printMessagesHTML();
					return $flash;
				},
			], 
			'data' => [
			], 
			'sort'     => -1
		];
}

return [

    /**
     * Settings for Which theme to use, theme directory is found by path and name.
     *
     * path: where is the base path to the theme directory, end with a slash.
     * name: name of the theme is mapped to a directory right below the path.
     */
    'settings' => [
        'path' => 'theme',
        'name' => '',
    ],

    
    /** 
     * Add default views.
     */
    'views' => $views,


    /** 
     * Data to extract and send as variables to the main template file.
     */
    'data' => [

        // Language for this page.
        'lang' => 'sv',

        // Append this value to each <title>
        'title_append' => ' | Coders Haven',

        // Stylesheets
        'stylesheets' => ['css/style.php'],

        // Inline style
        'style' => null,

        // Favicon
        'favicon' => 'favicon.ico',

        // Path to modernizr or null to disable
        'modernizr' => 'js/modernizr.js',

        // Path to jquery or null to disable
        'jquery' => null,

        // Array with javscript-files to include
        'javascript_include' => ["js/jquery/jquery-1.10.2.js", "js/jquery/jquery-1.9.2.js"],

        // Use google analytics for tracking, set key or null to disable
        'google_analytics' => null,
    ],
];

