<?php

namespace Infrastructure\Localization;

use Infrastructure\Localization\Contracts\TranslatorInterface;

class Translator implements TranslatorInterface
{
    /** @param array<string, mixed> $translations */
    public function __construct(
        private readonly array $translations,
        private string $defaultLanguage = 'en',
    ) {
    }

    public static function loadFromDirectory(string $dir): self
    {
        $languageFiles = array_filter(
            scandir($dir),
            fn ($file) => preg_match('/.+\.json/', $file)
        );

        $translations = [];
        foreach ($languageFiles as $languageFile) {
            $language = str_replace('.json', '', $languageFile);
            $translations[$language] = json_decode(file_get_contents("$dir/$languageFile"), true);
        }

        return new self($translations);
    }

    public function translate(string $key, array $parameters = [], string|null $language = null): string
    {
        $translation = $this->translations[$language ?? $this->defaultLanguage][$key] ?? $key;

        foreach ($parameters as $parameter => $value) {
            $translation = str_replace(":$parameter", $value, $translation);
        }

        return $translation;
    }

    public function setDefaultLanguage(string $language): void
    {
        $this->defaultLanguage = $language;
    }
}