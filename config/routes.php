<?php

use App\Controllers\HomeController;

$app->get('/', HomeController::class. ':getHome')->setName('home');
// $app->post('/', HomeController::class. ':postHome');

// Exemple pour le RouterJS
$app->get('/hello/{name}', HomeController::class. ':getHome')->setName('hello');
