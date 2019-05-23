<?php

$container = $app->getContainer();

// Session helper
$container['session'] = function () {
    return new \Adbar\Session("app");
};

// Monolog
$container['logger'] = function ($container) {
    $settings = [
      'name' => 'slim-app',
      'path' => dirname(__DIR__).'/storage/logs/app.log'
    ];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], Monolog\Logger::DEBUG));
    return $logger;
};

if (getenv('ENV') == 'dev') {
    $container['twig_profile'] = function () {
        return new Twig_Profiler_Profile();
    };
}

// Twig
$container['view'] = function ($container) {
    $pathView = dirname(__DIR__);

    if (slim_env('CACHE')) {
        $cache = $pathView.'/storage/cache';
    } else {
        $cache = false;
    }
    $view = new \Slim\Views\Twig($pathView.'/app/src/Views', [
        'cache' => $cache,
        'debug' => true
    ]);

    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));
    if (getenv('ENV') == 'dev') {
        $view->addExtension(new Twig_Extension_Profiler($container['twig_profile']));
        $view->addExtension(new Twig_Extension_Debug());
    }

    $defaultLang = 'en';
    $session = $container['session'];

    if (!$session->has('lang')) {
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && !is_null($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $lang = strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));
            if (file_exists(dirname(__DIR__).'/config/translations/'.$lang.".yml")) {
                $session->set('lang', $lang);
            } else {
                $session->set('lang', $defaultLang);
            }
        } else {
            $session->set('lang', $defaultLang);
        }
    }
    $translator = new \Symfony\Component\Translation\Translator(
        $session->get('lang'),
        null
    );
    $translator->setFallbackLocales([$defaultLang]);
    $translator->addLoader('yml', new \Symfony\Component\Translation\Loader\YamlFileLoader());
    $directory = new \RecursiveDirectoryIterator(
        dirname(__DIR__).'/config/translations/',
        \FilesystemIterator::SKIP_DOTS
    );
    $it = new \RecursiveIteratorIterator($directory, \RecursiveIteratorIterator::SELF_FIRST);
    $it->setMaxDepth(1);
    foreach ($it as $fileinfo) {
        if ($fileinfo->isFile() && $fileinfo->getFilename() != ".gitkeep") {
            $lang = explode(".", $fileinfo->getFilename());
            $translator->addResource(
                'yml',
                dirname(__DIR__).'/config/translations/'.$fileinfo->getFilename(),
                $lang[0]
            );
        }
    }
    $view->addExtension(new \Symfony\Bridge\Twig\Extension\TranslationExtension($translator));
    return $view;
};

// EntityManager de doctrine
$container['em'] = function () {
    if (getenv('ENV') == 'dev') {
        $db = "DB_DEV";
    } elseif (getenv('ENV') == 'prod') {
        $db = "DB_PROD";
    }
    $connection = [
        'url' => getenv($db)
    ];
    $config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(
        ['app/src/Entity'],
        true,
        dirname(__DIR__).'/storage/cache/doctrine',
        null,
        false
    );
    return \Doctrine\ORM\EntityManager::create($connection, $config);
};

// Csrf
$container['csrf'] = function () {
    $guard = new \Slim\Csrf\Guard();
    $guard->setFailureCallable(function ($request, $response, $next) {
        $request = $request->withAttribute("csrf_status", false);
        return $next($request, $response);
    });
    return $guard;
};
