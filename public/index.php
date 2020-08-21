<?php

use DI\Container;
use Slim\Factory\AppFactory;
use Dotenv\Dotenv;

// Set the absolute path to the root directory.
$rootPath = realpath(dirname(__DIR__));

// Autoload de composer
require $rootPath . '/vendor/autoload.php';

// Initialisation du .env
$dotenv = Dotenv::createImmutable($rootPath);
$dotenv->load(true);

// Initialisation session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Environnement
if ($_ENV["ENV"] == 'dev') {
    ini_set('display_errors', 'On');
    ini_set('display_startup_errors', 'On');
    ini_set('log_errors', 'On');
    $displayErrors = true;
} else {
    ini_set('display_errors', 'off');
    ini_set('display_startup_errors', 'off');
    ini_set('log_errors', 'off');
    $displayErrors = false;
}

// Create Container
$container = new Container();
AppFactory::setContainer($container);

// Create App
$app = AppFactory::create();
$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();
$app->addErrorMiddleware($displayErrors, false, false);

// Le container qui compose nos librairies
require $rootPath . '/config/container.php';

// Appel des middlewares
require $rootPath . '/config/middlewares.php';

// Le fichier ou l'on déclare les routes
require $rootPath . '/config/routes.php';

// Execution de Slim
$app->run();
