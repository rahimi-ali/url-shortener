<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LinksController;
use App\Http\Middleware\Authenticate;
use Infrastructure\Http\Contracts\RequestInterface;
use Infrastructure\Http\Contracts\RouteRegistrarInterface;

return function (RouteRegistrarInterface $routeRegistrar) {
    $routeRegistrar->post('auth/register', [AuthController::class, 'register']);
    $routeRegistrar->post('auth/login', [AuthController::class, 'login']);
    $routeRegistrar->get('auth/profile', [AuthController::class, 'profile'])
        ->addMiddleware([Authenticate::class, 'handle']);

    $routeRegistrar->get('links', [LinksController::class, 'index'])
        ->addMiddleware([Authenticate::class, 'handle']);
    $routeRegistrar->post('links', [LinksController::class, 'store'])
        ->addMiddleware([Authenticate::class, 'handle']);
    $routeRegistrar->delete('links/:id', [LinksController::class, 'delete'])
        ->addMiddleware([Authenticate::class, 'handle']);

    $routeRegistrar->get('(?<shortLink>\w+)', [LinksController::class, 'redirect']);
};