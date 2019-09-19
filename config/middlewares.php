<?php

use Slim\Views\TwigMiddleware;
use App\Middlewares;

$app->add(TwigMiddleware::createFromContainer($app));

// Middleware pour les message d'alert en session
$app->add(new Middlewares\AlertMiddleware($container));

// Middleware pour la sauvegarde des champs de saisie
$app->add(new Middlewares\OldMiddleware($container));

// Middleware pour la génération de token
$app->add(new Middlewares\TokenMiddleware($container));

// Middleware pour la vérification csrf
$app->add(new Middlewares\CsrfMiddleware($container));
$app->add('csrf');
