<?php

use Slim\App;
use Slim\Routing\RouteCollectorProxy;

use App\Controllers\HomeAction;
use App\Controllers\PersonAction;

return function (App $app) {
    $app->get('/', HomeAction::class);
    $app->get('/datatable-person', PersonAction::class.':datatable');
    $app->post('/', PersonAction::class.':add');
    $app->put('/{id}', PersonAction::class.':update');
    $app->delete('/', PersonAction::class.':delete');
};