<?php

namespace App\Rules;

use RahimiAli\PhpDto\RuleInterface;
use RahimiAli\PhpDto\Support\ValidationError;

class UrlRule implements RuleInterface
{
    public function validate(float|object|int|bool|array|string|null $value): true|ValidationError
    {
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            return new ValidationError('url', []);
        }

        return true;
    }
}