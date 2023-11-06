<?php

namespace App\Entities;

class User
{
    private int $id;
    private string $username;
    private string $password;
    private string $firstname;
    private string $lastname;

    public function getId(): int
    {
        return $this->id;
    }

    public function isNew(): bool
    {
        return ! isset($this->id);
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): User
    {
        $this->username = $username;
        return $this;
    }

    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }

    public function setPassword(string $password): User
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        return $this;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): User
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): User
    {
        $this->lastname = $lastname;
        return $this;
    }
}