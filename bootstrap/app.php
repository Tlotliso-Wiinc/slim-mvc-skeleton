<?php

use Respect\Validation\Validator as v;
use Illuminate\Database\Capsule\Manager as Capsule;

require __DIR__ . '/../vendor/autoload.php';

// get environment variables
$dotenv = new Dotenv\Dotenv(__DIR__ . '/../');
$dotenv->load();

// Instantiate the app
$settings = require __DIR__ . '/../config/settings.php';
$app = new \Slim\App($settings);

// Get the app dependencies container
$container = $app->getContainer();

// Set up eloquent ORM with the provided database settings
$capsule = new Capsule;
$capsule->addConnection($container['settings']['db']);
$capsule->bootEloquent();
$capsule->setAsGlobal();

// Set up a logger
require __DIR__ . '/../config/logger.php';

// Set up the authentication dependencies
require __DIR__ . '/../config/auth.php';

// Set up dependencies
require __DIR__ . '/../config/dependencies.php';

// Set up the controllers
require __DIR__ . '/../config/controllers.php';

// Set up the view dependencies
require __DIR__ . '/../config/view.php';

// Register middleware
require __DIR__ . '/../config/middleware.php';

// Set up validation rules
v::with('App\\Validation\\Rules\\');

// Register routes
require __DIR__ . '/../routes/web.php';
require __DIR__ . '/../routes/api.php';