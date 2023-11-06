<?php

namespace App\Repositories;

use App\Entities\User;

interface UserRepositoryInterface
{
    public function save(User $user): void;

    public function findById(int $id): User|null;

    public function findByUsername(string $username): User|null;
}