<?php

use Slim\Csrf\Guard;
use Slim\Views\Twig;

// Router
$container->set('router', function () use ($app) {
    return $app->getRouteCollector()->getRouteParser();
});

// Csrf
$container->set('csrf', function () use ($app) {
    $guard = new Guard($app->getResponseFactory());
    $guard->setFailureHandler(function (ServerRequestInterface $request, RequestHandlerInterface $handler) {
        $request = $request->withAttribute("csrf_status", false);
        return $handler->handle($request);
    });
    return $guard;
});

// Twig
$container->set('view', function () use ($rootPath, $app) {
    if (slim_env('CACHE')) {
        $cache = $rootPath.'/storage/cache';
    } else {
        $cache = false;
    }
    $view = Twig::create($rootPath.'/app/src/Views', [
        'cache' => $cache,
        'debug' => true
    ]);

    if ($_ENV["ENV"] == 'dev') {
        $view->addExtension(new Twig_Extension_Debug());
    }

    // Translation for twig
    $defaultLang = 'en';
    if (!isset($_SESSION['lang'])) {
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && !is_null($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $lang = strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));
            if (file_exists($rootPath.'/config/translations/'.$lang.".yml")) {
                $_SESSION['lang'] = $lang;
            } else {
                $_SESSION['lang'] = $defaultLang;
            }
        } else {
            $_SESSION['lang'] = $defaultLang;
        }
    }
    $translator = new \Symfony\Component\Translation\Translator(
        $_SESSION['lang'],
        null
    );
    $translator->setFallbackLocales([$defaultLang]);
    $translator->addLoader('yml', new \Symfony\Component\Translation\Loader\YamlFileLoader());
    $directory = new \RecursiveDirectoryIterator(
        $rootPath.'/config/translations/',
        \FilesystemIterator::SKIP_DOTS
    );
    $it = new \RecursiveIteratorIterator($directory, \RecursiveIteratorIterator::SELF_FIRST);
    $it->setMaxDepth(1);
    foreach ($it as $fileinfo) {
        if ($fileinfo->isFile() && $fileinfo->getFilename() != ".gitkeep") {
            $lang = explode(".", $fileinfo->getFilename());
            $translator->addResource(
                'yml',
                $rootPath.'/config/translations/'.$fileinfo->getFilename(),
                $lang[0]
            );
        }
    }
    $view->addExtension(new \Symfony\Bridge\Twig\Extension\TranslationExtension($translator));
    return $view;
});

// Monolog
$container->set('logger', function () use ($rootPath) {
    $settings = [
        'name' => 'slim-app',
        'path' => $rootPath.'/storage/logs/app.log'
    ];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], Monolog\Logger::DEBUG));
    return $logger;
});

// EntityManager de doctrine
$container->set('em', function () use ($rootPath) {
    if ($_ENV["ENV"] == 'dev') {
        $db = "DB_DEV";
    } elseif ($_ENV["ENV"] == 'prod') {
        $db = "DB_PROD";
    }
    $connection = [
        'url' => $_ENV[$db]
    ];
    $config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(
        ['app/src/Entity'],
        true,
        $rootPath.'/storage/cache/doctrine',
        null,
        false
    );
    return \Doctrine\ORM\EntityManager::create($connection, $config);
});
