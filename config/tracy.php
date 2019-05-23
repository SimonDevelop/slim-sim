<?php
use Tracy\Debugger;

defined('DS') || define('DS', DIRECTORY_SEPARATOR);
define('DIR', realpath(dirname(__DIR__) . '/') . DS);
Debugger::enable(Debugger::DEVELOPMENT, DIR . 'storage/logs');
//Debugger::enable(Debugger::PRODUCTION, DIR . 'storage/log');
Debugger::timer();
return [
    'settings' => [
        'displayErrorDetails' => true,
        'translations_path' => DIR . '/config/translations/',
        'determineRouteBeforeAppMiddleware' => true,
        'addContentLengthHeader' => false,// if true = Unexpected data in output buffer
        // 'routerCacheFile' => DIR . 'var/cache/fastroute.cache',// uncomment after debug
        'db' => [// multi database configuration
            'default' => 'mysql',
            'connections' => [
                'mysql' => [
                    'url' => getenv('DB_DEV'),
                    // 'engine' => 'MyISAM',
                    'engine' => 'InnoDB',
                    'charset' => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                    'prefix' => ''
                ]
            ],
        ],
        'view' => [// Twig settings
            'template_path' => DIR . 'app/src/Views',
            'twig' => [
                'cache' => DIR . 'storage/cache/twig',
                'debug' => true,
            ],
        ],
        'logger' => [// monolog settings
            'name' => 'tracy',
            'level' => \Monolog\Logger::DEBUG,
            'path' => DIR . 'storage/logs/tracy.log',
            'maxFiles' => 15
        ],
        'tracy' => [
            'showPhpInfoPanel' => 1,
            'showSlimRouterPanel' => 1,
            'showSlimEnvironmentPanel' => 1,
            'showSlimRequestPanel' => 1,
            'showSlimResponsePanel' => 1,
            'showSlimContainer' => 1,
            'showEloquentORMPanel' => 0,
            'showIdiormPanel' => 0,
            'showDoctrinePanel' => 'em',
            'showTwigPanel' => 1,
            'showProfilerPanel' => 1,
            'showVendorVersionsPanel' => 1,
            'showXDebugHelper' => 0,
            'showIncludedFiles' => 0,
            'showConsolePanel' => 0,
            'configs' => [
                // XDebugger IDE key
                'XDebugHelperIDEKey' => 'PHPSTORM',
                // Disable login (don't ask for credentials, be careful) values ( 1 | 0 )
                'ConsoleNoLogin' => 0,
                // Multi-user credentials values( ['user1' => 'password1', 'user2' => 'password2'] )
                'ConsoleAccounts' => [
                    'dev' => '34c6fceca75e456f25e7e99531e2425c6c1de443'// = sha1('dev')
                ],
                // Password hash algorithm (password must be hashed) values('md5', 'sha256' ...)
                'ConsoleHashAlgorithm' => 'sha1',
                // Home directory (multi-user mode supported) values ( var || array )
                // '' || '/tmp' || ['user1' => '/home/user1', 'user2' => '/home/user2']
                'ConsoleHomeDirectory' => DIR,
                // terminal.js full URI
                'ConsoleTerminalJs' => '/assets/js/jquery.terminal.min.js',
                // terminal.css full URI
                'ConsoleTerminalCss' => '/assets/css/jquery.terminal.min.css',
                'ProfilerPanel' => [
                    // Memory usage 'primaryValue' set as Profiler::enable() or Profiler::enable(1)
                    // 'primaryValue' =>                   'effective',    // or 'absolute'
                    'show' => [
                        'memoryUsageChart' => 1, // or false
                        'shortProfiles' => true, // or false
                        'timeLines' => true // or false
                    ]
                ]
            ]
        ]
    ]
];
