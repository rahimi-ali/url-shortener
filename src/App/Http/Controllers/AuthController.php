<?php

namespace App\Http\Controllers;

use App\Entities\User;
use App\Exceptions\IncorrectCredentialsException;
use App\Exceptions\UnprocessableContentException;
use App\Exceptions\UserAlreadyExistsException;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Http\Responses\LoginResponse;
use App\Http\Responses\ProfileResponse;
use App\Repositories\UserRepositoryInterface;
use App\Services\AuthService;
use App\Services\Validator;
use Infrastructure\Http\Contracts\RequestInterface;
use Infrastructure\Localization\Contracts\TranslatorInterface;
use RahimiAli\PhpDto\Support\ValidationError;
use RahimiAli\PhpDto\ValidationException;

class AuthController
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly AuthService $authService,
        private readonly Validator $validator,
    ) {
    }

    public function register(RequestInterface $request): LoginResponse
    {
        $data = $this->validator->validate(RegistrationRequest::class, $request);

        $existingUser = $this->userRepository->findByUsername($data->getUsername());
        if ($existingUser !== null) {
            throw new UserAlreadyExistsException();
        }

        $user = new User();
        $user->setUsername($data->getUsername());
        $user->setPassword($data->getPassword());
        $user->setFirstname($data->getFirstname());
        $user->setLastname($data->getLastname());
        $this->userRepository->save($user);

        $token = $this->authService->login($user);

        return new LoginResponse($user, $token);
    }

    /** @throws IncorrectCredentialsException */
    public function login(RequestInterface $request): LoginResponse
    {
        $data = $this->validator->validate(LoginRequest::class, $request);

        $user = $this->userRepository->findByUsername($data->getUsername());

        if ($user === null || !$user->verifyPassword($data->getPassword())) {
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