<?php

namespace App\Repositories;

use App\Entities\Link;

interface LinkRepositoryInterface
{
    public function save(Link $link): void;

    public function getByUserId(int $userId): array;

    public function findByShortLink(string $shortLink): Link|null;

    public function findById(int $id): Link|null;

    public function deleteById(int $id): void;
}