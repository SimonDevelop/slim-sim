<?php
namespace App\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Csrf\Guard;

class CsrfMiddleware implements Middleware
{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        $csrf = $this->container->get("csrf");
        $this->container->get("view")->getEnvironment()
            ->addFunction(new \Twig_SimpleFunction('csrf', function () use ($csrf, $request) {
                $nameKey = $csrf->getTokenNameKey();
                $valueKey = $csrf->getTokenValueKey();
                $name = $request->getAttribute($nameKey);
                $value = $request->getAttribute($valueKey);
                return "<input type=\"hidden\" name=\"$nameKey\" value=\"$name\">
                        <input type=\"hidden\" name=\"$valueKey\" value=\"$value\">";
            }, ['is_safe' => ['html']]));
        return $handler->handle($request);
    }
}
