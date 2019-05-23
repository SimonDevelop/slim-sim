<?php
namespace App\Middlewares;

use Slim\Http\Request;
use Slim\Http\Response;

class AlertMiddleware
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
            'alert',
            $this->container->session->has('alert') ? $this->container->session->get('alert') : []
        );
        if ($this->container->session->has('alert')) {
            $this->container->session->delete('alert');
        }
        return $next($request, $response);
    }
}
