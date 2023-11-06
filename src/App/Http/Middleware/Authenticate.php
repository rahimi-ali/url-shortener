<?php

namespace App\Http\Middleware;

use App\Exceptions\UnauthorizedException;
use App\Services\AuthService;
use Infrastructure\Http\Contracts\RequestInterface;

class Authenticate
{
    public function __construct(
        private readonly AuthService $authService,
    ) {
    }

    public function handle(RequestInterface $request, $next)
    {
        $authenticated = $this->authService->authenticateRequest($request);

        if (!$authenticated) {
            throw new UnauthorizedException();
        }

        return $next($request);
    }
}