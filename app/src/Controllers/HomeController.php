<?php
namespace App\Controllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HomeController extends Controller
{
    public function getHome(RequestInterface $request, ResponseInterface $response)
    {
        if ($request->getAttribute('name')) {
            $user = $request->getAttribute('name');
        } else {
            // Exemple session helper
            if (!$this->session->has('user')) {
                $this->session->set('user', 'John');
            }
            $user = $this->session->get('user');
        }

        // Passer en langue française (un second rafraichissement est necessaire)
        // $this->session->set('lang', 'fr');

        // Exemple doctrine
        // $users = $this->em->getRepository('App\Entity\User')->queryGetUsers();

        // Exemple monolog
        $this->logger->addInfo("Bienvenue ".$user);

        // Exemple d'alerte
        if ($this->session->has('lang') && $this->session->get('lang') == "fr") {
            $this->alert(["Ceci est un message d'alerte de test"], 'danger');
        } else {
            $this->alert(["This is a test alert message"], 'danger');
        }

        $params = compact("user");
        $this->render($response, 'pages/home.twig', $params);
    }

    public function postHome(RequestInterface $request, ResponseInterface $response)
    {
        return $this->redirect($response, 'home');
    }
}
