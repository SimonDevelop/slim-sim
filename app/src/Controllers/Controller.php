<?php
namespace App\Controllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Controller
{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function __get($name)
    {
        return $this->container->get($name);
    }

    public function alert($message, $type = "success")
    {
        if (!$this->session->has('alert')) {
            $this->session->set('alert', []);
        }
        return $this->session->add([
            'alert' => [$type => $message]
        ]);
    }

    public function tokenCheck($token)
    {
        if (!$this->session->has('token')) {
            return false;
        } elseif ($this->session->get('token') === $token) {
            return true;
        } else {
            return false;
        }
    }

    public function render(ResponseInterface $response, $file, $params = [])
    {
        $this->container->view->render($response, $file, $params);
    }

    public function redirect(ResponseInterface $response, $name, $status = 302, $params = [])
    {
        if (empty($params)) {
            return $response->withStatus($status)->withHeader('Location', $this->router->pathFor($name));
        } else {
            return $response->withStatus($status)->withHeader('Location', $this->router->pathFor($name, $params));
        }
    }
}
