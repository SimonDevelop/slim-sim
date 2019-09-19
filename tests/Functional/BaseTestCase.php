<?php

namespace Tests\Functional;

use DI\Container;
use Exception;
use PHPUnit\Framework\TestCase as PHPUnit_TestCase;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Request as SlimRequest;
use Slim\Psr7\Uri;

/**
 * This is an example class that shows how you could set up a method that
 * runs the application. Note that it doesn't cover all use-cases and is
 * tuned to the specifics of this skeleton app, so if your needs are
 * different, you'll need to change it.
 */
class BaseTestCase extends PHPUnit_TestCase
{
    /**
     * @return App
     * @throws Exception
     */
    protected function getAppInstance(): App
    {
        // Set the absolute path to the root directory.
        $rootPath = realpath(dirname(__DIR__, 2));

        $dotenv = \Dotenv\Dotenv::create($rootPath);
        $dotenv->load(true);

        // Create Container
        $container = new Container();
        AppFactory::setContainer($container);

        // Create App
        $app = AppFactory::create();
        $app->addRoutingMiddleware();

        // Le container qui compose nos librairies
        require $rootPath . '/config/container.php';

        // Appel des middlewares
        require $rootPath . '/config/middlewares.php';

        // Le fichier ou l'on déclare les routes
        require $rootPath . '/config/routes.php';

        return $app;
    }

    /**
     * @param string $method
     * @param string $path
     * @param array  $headers
     * @param array  $serverParams
     * @param array  $cookies
     * @return Request
     */
    protected function createRequest(
        string $method,
        string $path,
        array $headers = [],
        array $serverParams = [],
        array $cookies = []
    ): Request {
        $uri = new Uri('', '', 80, $path);
        $handle = fopen('php://temp', 'w+');
        $stream = (new StreamFactory())->createStreamFromResource($handle);

        $h = new Headers();
        foreach ($headers as $name => $value) {
            $h->addHeader($name, $value);
        }

        return new SlimRequest($method, $uri, $h, $cookies, $serverParams, $stream);
    }
}
