<?php
namespace App\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class OldMiddleware implements Middleware
{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        if (isset($_SESSION['old']) && !empty($_SESSION['old'])) {
            $this->container->get("view")->getEnvironment()->addGlobal('old', $_SESSION['old']);
            unset($_SESSION['old']);
        } else {
            $this->container->get("view")->getEnvironment()->addGlobal('old', []);
        }
        return $handler->handle($request);
    }
}
