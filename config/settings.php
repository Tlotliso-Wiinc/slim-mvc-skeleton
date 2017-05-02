<?php

return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production

        // Database settings
        'db' => [
			'driver' => getenv('DB_CONNECTION'),
		  	'host' => getenv('DB_HOST'),
		  	'port' => getenv('DB_PORT'),
		  	'database' => getenv('DB_DATABASE'),
		  	'username' => getenv('DB_USERNAME'),
		  	'password' => getenv('DB_PASSWORD'),
		  	'charset' => 'utf8',
		  	'collation' => 'utf8_unicode_ci'
		],
    ],
];
