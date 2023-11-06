<?php

namespace App\Services;

use App\Entities\User;
use App\Repositories\UserRepositoryInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Infrastructure\Http\Contracts\RequestInterface;
use Throwable;

class AuthService
{
    private User|null $authenticatedUser = null;

    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly string $privateKey,
        private readonly string $publicKey,
        private readonly int $tokenTtl = 3600,
    ) {
    }

    public function login(User $user): string
    {
        $this->authenticatedUser = $user;
        return $this->generateToken($user);
    }

    public function authenticateRequest(RequestInterface $request): bool
    {
        $authorization = $request->getHeader('Authorization')[0] ?? null;

        if ($authorization === null) {
            return false;
        }

        if (preg_match('/Bearer (.+)/', $authorization, $matches) !== false) {
            $bearer = $matches[1];
            $id = $this->decodeToken($bearer);
            if ($id === null) {
                return false;
            }

            $user = $this->userRepository->findById($id);
            if ($user === null) {
                return false;
            }

            $this->authenticatedUser = $user;
            return true;
        }

        return false;
    }

    public function getAuthenticatedUser(): User|null
    {
        return $this->authenticatedUser;
    }

    private function generateToken(User $user): string
    {
        $payload = [
            'iat' => time(),
            'exp' => time() + $this->tokenTtl,
            'id' => $user->getId(),
        ];

        return JWT::encode($payload, $this->privateKey, 'RS256');
    }

    private function decodeToken(string $token): int|null
    {
        try {
            $decoded = JWT::decode($token, new Key($this->publicKey, 'RS256'));
            return $decoded->id;
        } catch (Throwable) {
            return null;
        }
    }
}