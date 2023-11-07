<?php

namespace App\Http\Requests;

use App\Rules\UrlRule;
use RahimiAli\PhpDto\Dto;
use RahimiAli\PhpDto\Facades\Rule;
use RahimiAli\PhpDto\Facades\Type;
use RahimiAli\PhpDto\Field;

class CreateLinkRequest extends Dto
{
    protected string $uri;

    public static function fields(array $data): array
    {
        return [
            'uri' => new Field(
                property: 'uri',
                type: Type::string(),
                rules: [
                    new UrlRule(),
                    Rule::maxLength(1000),
                ]
            ),
        ];
    }

    public function getUri(): string
    {
        return $this->uri;
    }
}