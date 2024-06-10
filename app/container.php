<?php

use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Factory\AppFactory;

$capsule = null;

(Dotenv\Dotenv::createImmutable(__DIR__ . '/..'))->load();

function ngetenv(string $key, string $default)
{
    $env = isset($_ENV[$key]) ? $_ENV[$key] : $default;
    return $env;
}

return [
    'settings' => [
        'db'    => [
            'driver'    => 'mysql',
            'host'      => ngetenv('DB_HOST', 'localhost'),
            'database'  => ngetenv('DB_NAME', 'slim'),
            'username'  => ngetenv('DB_USER', 'root'),
            'password'  => ngetenv('DB_PASS', ''),
            'port'      => ngetenv('DB_PORT', '3306'),
            'charset'   => ngetenv('DB_CHARSET', 'utf8'),
            'collation' => ngetenv('DB_COLLATION', 'utf8_unicode_ci'),
            'prefix'    => ngetenv('DB_PREFIX', ''),
        ],
        'encryption_key'            => ngetenv('ENCRYPTION_KEY', 'miong'),
        'blind_server_credential'   => ngetenv('CREDENTIALS_TO_JS', 'miong')
    ],
    App::class => function (ContainerInterface $container) use ($capsule) {
        $app = AppFactory::createFromContainer($container);

        $settings = $container->get('settings');

        $dbSettings = $settings['db'];
        $capsule = new \Illuminate\Database\Capsule\Manager;
        $capsule->addConnection($dbSettings, 'default');
        $capsule->bootEloquent();
        $capsule->setAsGlobal();
        $container->set('db', $capsule);

        (require __DIR__ . '/routes.php')($app);

        return $app;
    }
];