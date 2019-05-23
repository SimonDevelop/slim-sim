<?php

if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

// Autoload de composer
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Initialisation du .env
$dotenv = \Dotenv\Dotenv::create(dirname(__DIR__));
$dotenv->load(true);

// Initialisation session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Initialisation de Slim en fonction de l'environnement de développement
if (getenv('ENV') == 'dev') {
    error_reporting(-1);
    ini_set('display_errors', 'On');
    ini_set('display_startup_errors', 'On');
    ini_set('log_errors', 'On');
    RunTracy\Helpers\Profiler\Profiler::enable();

    RunTracy\Helpers\Profiler\Profiler::start('loadSettings');
    $c = require_once dirname(__DIR__).'/config/tracy.php';
    RunTracy\Helpers\Profiler\Profiler::finish('loadSettings');

    RunTracy\Helpers\Profiler\Profiler::start('initApp');
    $app = new \Slim\App($c);
    RunTracy\Helpers\Profiler\Profiler::finish('initApp');

    // Register dependencies
    RunTracy\Helpers\Profiler\Profiler::start('RegisterDependencies');
    require_once dirname(__DIR__).'/config/container.php';
    RunTracy\Helpers\Profiler\Profiler::finish('RegisterDependencies');

    // Register middleware
    RunTracy\Helpers\Profiler\Profiler::start('RegisterMiddlewares');
    require_once dirname(__DIR__).'/config/middlewares.php';
    RunTracy\Helpers\Profiler\Profiler::finish('RegisterMiddlewares');

    // Register routes
    RunTracy\Helpers\Profiler\Profiler::start('RegisterRoutes');
    // RouterJS (générer des routes slim coté javascript)
    $app->get('/routerjs', function ($req, $res, $args) {
        $routerJs = new \Llvdl\Slim\RouterJs($this->router, false);
        return $routerJs->getRouterJavascriptResponse();
    })->setName('routerjs');

    // Le fichier ou l'on déclare les routes
    require_once dirname(__DIR__).'/config/routes.php';
    RunTracy\Helpers\Profiler\Profiler::finish('RegisterRoutes');

    require_once dirname(__DIR__).'/config/error_pages.php';

    // Run app
    RunTracy\Helpers\Profiler\Profiler::start('runApp, %s, line %s', basename(__FILE__), __LINE__);
    $app->run();
    RunTracy\Helpers\Profiler\Profiler::finish('runApp, %s, line %s', basename(__FILE__), __LINE__);
} else {
    $app = new \Slim\App([
        'translations_path' => dirname(__DIR__).'/config/translations/'
    ]);

    // Le container qui compose nos librairies
    require_once dirname(__DIR__).'/config/container.php';

    // Appel des middlewares
    require_once dirname(__DIR__).'/config/middlewares.php';

    // RouterJS (générer des routes slim coté javascript)
    $app->get('/routerjs', function ($req, $res, $args) {
        $routerJs = new \Llvdl\Slim\RouterJs($this->router);
        return $routerJs->getRouterJavascriptResponse();
    })->setName('routerjs');

    // Le fichier ou l'on déclare les routes
    require_once dirname(__DIR__).'/config/routes.php';

    require_once dirname(__DIR__).'/config/error_pages.php';

    // Execution de Slim
    $app->run();
}
