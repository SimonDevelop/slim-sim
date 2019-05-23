<?php

namespace Tests\Functional;

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Environment;
use PHPUnit\Framework\TestCase;

/**
 * This is an example class that shows how you could set up a method that
 * runs the application. Note that it doesn't cover all use-cases and is
 * tuned to the specifics of this skeleton app, so if your needs are
 * different, you'll need to change it.
 */
class BaseTestCase extends TestCase
{
    /**
     * Use middleware when running application?
     *
     * @var bool
     */
    protected $withMiddleware = true;

    /**
     * Process the application given a request method and URI
     *
     * @param string $requestMethod the request method (e.g. GET, POST, etc.)
     * @param string $requestUri the request URI
     * @param array|object|null $requestData the request data
     * @return \Slim\Http\Response
     */
    public function runApp($requestMethod, $requestUri, $requestData = null)
    {
        // Create a mock environment for testing with
        $environment = Environment::mock(
            [
                'REQUEST_METHOD' => $requestMethod,
                'REQUEST_URI' => $requestUri
            ]
        );

        // Set up a request object based on the environment
        $request = Request::createFromEnvironment($environment);

        // Add request data, if it exists
        if (isset($requestData)) {
            $request = $request->withParsedBody($requestData);
        }

        // Set up a response object
        $response = new Response();

        // Use the application settings
        $dotenv = \Dotenv\Dotenv::create(dirname(__DIR__, 2).'/');
        $dotenv->load(true);

        // Instantiate the application
        $app = new App();

        // Set up dependencies
        require_once dirname(__DIR__, 2).'/config/container.php';

        // Register middleware
        if ($this->withMiddleware) {
            $_SESSION = [];
            require_once dirname(__DIR__, 2).'/config/middlewares.php';
        }

        // RouterJS
        $app->get('/routerjs', function ($req, $res, $args) {
            $routerJs = new \Llvdl\Slim\RouterJs($this->router, true);
            return $routerJs->getRouterJavascriptResponse();
        })->setName('routerjs');

        // Register routes
        require_once dirname(__DIR__, 2).'/config/routes.php';

        // Process the application
        $response = $app->process($request, $response);

        // Return the response
        return $response;
    }
}
