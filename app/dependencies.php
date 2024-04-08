<?php

declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use DiDom\Document;
use GuzzleHttp\Client;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Slim\Flash\Messages;
use Slim\Views\Twig;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        PDO::class => function (ContainerInterface $c) {
            /*$settings = $c->get(SettingsInterface::class);
            $dbSettings = $settings->get('db');
            $pdo = new PDO('pgsql:
                host=' . $dbSettings['host'] . ';
                port=' . $dbSettings['port'] . ';
                dbname=' . $dbSettings['name'] . ';
                user=' . $dbSettings['user'] . ';
                password=' . $dbSettings['pass'] . ';');*/

            $databaseUrl = parse_url($_ENV['DATABASE_URL']);
            $username = $databaseUrl['user']; // janedoe
            $password = $databaseUrl['pass']; // mypassword
            $host = $databaseUrl['host']; // localhost
            $port = $databaseUrl['port']; // 5432
            $dbName = ltrim($databaseUrl['path'], '/');
            $pdo = new PDO('pgsql:
                host=' . $host . ';
                port=' . $port . ';
                dbname=' . $dbName . ';
                user=' . $username . ';
                password=' . $password . ';');

            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            return $pdo;
        },
        Twig::class => function () {
            return Twig::create(__DIR__ . '/../templates');
        },
        Messages::class => function () {
            return new Messages();
        },
        Client::class => function () {
            return new Client();
        },
    ]);
};
