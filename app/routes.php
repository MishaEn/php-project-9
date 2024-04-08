<?php

declare(strict_types=1);

use App\Application\Actions\Url\AddUrlAction;
use App\Application\Actions\Url\CheckUrlAction;
use App\Application\Actions\Url\IndexUrlAction;
use App\Application\Actions\Url\ListUrlAction;
use App\Application\Actions\Url\ViewUrlAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Slim\Flash;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', IndexUrlAction::class);

    $app->group('/urls', function (Group $group) {
        $group->get('', ListUrlAction::class);
        $group->post('', AddUrlAction::class);
        $group->get('/{id}', ViewUrlAction::class);
        $group->post('/{id}/checks', CheckUrlAction::class);
    });
};
