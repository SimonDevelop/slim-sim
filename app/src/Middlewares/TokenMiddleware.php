<?php
namespace App\Middlewares;

use Slim\Http\Request;
use Slim\Http\Response;

class TokenMiddleware
{
    private $twig;
    private $container;

    public function __construct(\Twig_Environment $twig, $container)
    {
        $this->twig = $twig;
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, $next)
    {
        if (!$this->container->session->has('token')) {
            $this->container->session->set('token', uniqid());
        }
        $this->twig->addGlobal('token', $this->container->session->get('token'));
        $response = $next($request, $response);
        if ($response->getStatusCode() === 400) {
            $this->container->session->set('token', $request->getParams());
        }
        return $response;
    }
}
