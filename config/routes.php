<?php

use App\Controllers\HomeController;

$app->get('/', HomeController::class. ':getHome')->setName('home');
$app->get('/hello/{name}', HomeController::class. ':getHome')->setName('hello');
// $app->post('/', HomeController::class. ':postHome');
