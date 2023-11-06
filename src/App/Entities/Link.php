<?php

namespace App\Entities;

class Link
{
    private int $id;
    private int $userId;
    private string $uri;
    private string $shortLink;

    public function getId(): int
    {
        return $this->id;
    }

    public function isNew(): bool
    {
        return !isset($this->id);
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function setUri(string $uri): void
    {
        $this->uri = $uri;
    }

    public function getShortLink(): string
    {
        return $this->shortLink;
    }

    public function setShortLink(string $shortLink): void
    {
        $this->shortLink = $shortLink;
    }
}