<?php

use App\Http\Controllers\AuthController;
use App\Http\Middleware\Authenticate;
use Infrastructure\Http\Contracts\RequestInterface;
use Infrastructure\Http\Contracts\RouteRegistrarInterface;

return function (RouteRegistrarInterface $routeRegistrar) {
    $routeRegistrar->post('auth/register', [AuthController::class, 'register']);
    $routeRegistrar->post('auth/login', [AuthController::class, 'login']);
    $routeRegistrar->get('auth/profile', [AuthController::class, 'profile'])
        ->addMiddleware([Authenticate::class, 'handle']);

    $routeRegistrar->get('links', fn() => dd('links index'));
    $routeRegistrar->post('links', fn() => dd('create link'));
    $routeRegistrar->delete('links/:id', fn(RequestInterface $req) => dd('get link', $req->routeParam('id')));

    $routeRegistrar->get('(?<link>\w+)', fn(RequestInterface $req) => dd('match any', $req->routeParam('link')));
};