<?php

namespace App\Http\Responses;

use App\Entities\User;
use Laminas\Diactoros\Response\JsonResponse;

class ProfileResponse extends JsonResponse
{
    public function __construct(User $user)
    {
        parent::__construct([
            'user' => [
                'id' => $user->getId(),
                'firstname' => $user->getFirstname(),
                'lastname' => $user->getLastname(),
            ],
        ]);
    }
}