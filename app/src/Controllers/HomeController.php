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
            if (!isset($_SESSION["user"])) {
                $_SESSION["user"] = "John";
            }
            $user = $_SESSION["user"];
        }

        // Passer en langue française (un second rafraichissement est necessaire)
        // $_SESSION["lang"] = "fr";

        // Exemple doctrine
        // $users = $this->em->getRepository('App\Entity\User')->queryGetUsers();

        // Exemple monolog
        $this->logger->addInfo("Bienvenue ".$user);

        // Exemple d'alerte
        if (isset($_SESSION["lang"]) && $_SESSION["lang"] == "fr") {
            $this->alert(["Ceci est un message d'alerte de test"], 'danger');
        } else {
            $this->alert(["This is a test alert message"], 'danger');
        }

        $params = compact("user");
        return $this->render($response, 'pages/home.twig', $params);
    }

    public function postHome(RequestInterface $request, ResponseInterface $response)
    {
        return $this->redirect($response, 'home');
    }
}
