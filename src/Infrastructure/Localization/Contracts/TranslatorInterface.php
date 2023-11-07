<?php

namespace Infrastructure\Localization\Contracts;

interface TranslatorInterface
{
    public function translate(string $key, array $parameters = [], string|null $language = null): string;

    public function setDefaultLanguage(string $language): void;
}