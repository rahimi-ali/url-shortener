<?php

namespace App\Http\Controllers;

use App\Entities\User;
use App\Exceptions\IncorrectCredentialsException;
use App\Exceptions\UserAlreadyExistsException;
use App\Http\Responses\LoginResponse;
use App\Http\Responses\ProfileResponse;
use App\Repositories\UserRepositoryInterface;
use App\Services\AuthService;
use Infrastructure\Http\Contracts\RequestInterface;

class AuthController
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly AuthService $authService,
    ) {
    }

    public function register(RequestInterface $request): LoginResponse
    {
        $data = json_decode($request->getBody()->getContents(), true);

        $existingUser = $this->userRepository->findByUsername($data['username']);
        if ($existingUser !== null) {
            throw new UserAlreadyExistsException();
        }

        $user = new User();
        $user->setUsername($data['username']);
        $user->setPassword($data['password']);
        $user->setFirstname($data['firstname']);
        $user->setLastname($data['lastname']);
        $this->userRepository->save($user);

        $token = $this->authService->login($user);

        return new LoginResponse($user, $token);
    }

    /** @throws IncorrectCredentialsException */
    public function login(RequestInterface $request): LoginResponse
    {
        $data = json_decode($request->getBody()->getContents(), true);

        $user = $this->userRepository->findByUsername($data['username']);

        if ($user === null || !$user->verifyPassword($data['password'])) {
            throw new IncorrectCredentialsException();
        }

        $token = $this->authService->login($user);

        return new LoginResponse($user, $token);
    }

    public function profile(): ProfileResponse
    {
        $user = $this->authService->getAuthenticatedUser();

        return new ProfileResponse($user);
    }
}