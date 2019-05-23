<?php
namespace App\Middlewares;

use Slim\Http\Request;
use Slim\Http\Response;

class PregReplace
{
    private $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function __invoke(Request $request, Response $response, $next)
    {
        return $next($request, $response);
    }
}
