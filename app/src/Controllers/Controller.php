<?php
namespace App\Controllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Container\ContainerInterface;

class Controller
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __get($name)
    {
        return $this->container->get($name);
    }

    public function alert($message, $type = "success")
    {
        if (!isset($_SESSION['alert2'])) {
            $_SESSION['alert2'] = [];
        }
        $_SESSION['alert2'][$type] = $message;
    }

    public function tokenCheck($token)
    {
        if (!isset($_SESSION['token']) || empty($_SESSION['token'])) {
            return false;
        } elseif ($_SESSION['token'] === $token) {
            return true;
        } else {
            return false;
        }
    }

    public function render(ResponseInterface $response, $file, $params = [])
    {
        return $this->container->get("view")->render($response, $file, $params);
    }

    public function redirect(ResponseInterface $response, $name, $status = 302, $params = [])
    {
        if (empty($params)) {
            return $response->withHeader('Location', $this->container->get("router")->urlFor($name))
                ->withStatus($status);
        } else {
            return $response->withHeader('Location', $this->container->get("router")->urlFor($name, $params))
                ->withStatus($status);
        }
    }
}
