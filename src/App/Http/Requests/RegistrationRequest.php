<?php

namespace App\Http\Requests;

use RahimiAli\PhpDto\Dto;
use RahimiAli\PhpDto\Facades\Rule;
use RahimiAli\PhpDto\Facades\Type;
use RahimiAli\PhpDto\Field;

class RegistrationRequest extends Dto
{
    protected string $username;
    protected string $password;
    protected string $firstname;
    protected string $lastname;

    public static function fields(array $data): array
    {
        return [
            'username' => new Field(
                property: 'username',
                type: Type::string(),
                rules: [
                    Rule::minLength(3),
                    Rule::maxLength(250),
                ]
            ),
            'password' => new Field(
                property: 'password',
                type: Type::string(),
                rules: [
                    Rule::minLength(6),
                    Rule::maxLength(250),
                ]
            ),
            'firstname' => new Field(
                property: 'firstname',
                type: Type::string(),
                rules: [
                    Rule::minLength(3),
                    Rule::maxLength(250),
                ]
            ),
            'lastname' => new Field(
                property: 'lastname',
                type: Type::string(),
                rules: [
                    Rule::minLength(3),
                    Rule::maxLength(250),
                ]
            ),
        ];
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }
}