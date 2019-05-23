<?php
namespace App\Middlewares;

use Slim\Http\Request;
use Slim\Http\Response;

class OldMiddleware
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
        $this->twig->addGlobal(
            'old',
            $this->container->session->has('old') ? $this->container->session->get('old') : []
        );
        if ($this->container->session->has('old')) {
            $this->container->session->delete('old');
        }
        $response = $next($request, $response);
        if ($response->getStatusCode() === 400) {
            $this->container->session->set('old', $request->getParams());
            $response = $response->withStatus(302);
        }
        return $response;
    }
}
