<?php

// Commentez la condition "if" pour l'utilisation de ces pages en mode developpement
if (getenv('ENV') == 'prod') {
  // 404
    $container['notFoundHandler'] = function ($container) {
        return function ($request, $response) use ($container) {
            $container->view->offsetSet('erno', '404');
            $container->view->offsetSet('ermes', 'Page not found');
            $container->view->offsetSet('uri', $request->getUri());
            return $container->view->render($response, 'error.twig', [
                "title" => "404", "message" => "Page not found"
            ])->withStatus(404);
        };
    };
    // 405
    $container['notAllowedHandler'] = function ($container) {
        return function ($request, $response, $methods) use ($container) {
            $container->view->offsetSet('erno', '405');
            $container->view->offsetSet('ermes', 'Can not route with method{s}: ' . implode(', ', $methods));
            $container->view->offsetSet('uri', $request->getUri());
            return $container->view->render($response, 'error.twig', [
                "title" => "405", "message" => "Can not route with method{s}: " . implode(', ', $methods)
            ])->withStatus(405);
        };
    };
    // 500
    $container['errorHandler'] = function ($container) {
        return function ($request, $response, $exception) use ($container) {
            $container->view->offsetSet('erno', '500');
            $container->view->offsetSet('ermes', 'Something went wrong!');
            $container->view->offsetSet('uri', $request->getUri());
            return $container->view->render($response, 'error.twig', [
                "title" => "500", "message" => "Something went wrong!"
            ])->withStatus(500);
        };
    };
}
