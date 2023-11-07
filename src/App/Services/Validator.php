<?php

namespace App\Services;

use App\Exceptions\UnprocessableContentException;
use Infrastructure\Localization\Contracts\TranslatorInterface;
use Psr\Http\Message\ServerRequestInterface;
use RahimiAli\PhpDto\Dto;
use RahimiAli\PhpDto\Support\ValidationError;
use RahimiAli\PhpDto\ValidationException;

class Validator
{
    public function __construct(
        private readonly TranslatorInterface $translator,
    ) {
    }

    /**
     * @template T of Dto
     * @param class-string<T> $dtoClass
     * @param ServerRequestInterface $request
     * @return T
     * @throws UnprocessableContentException
     */
    public function validate(string $dtoClass, ServerRequestInterface $request): object
    {
        try {
            return $dtoClass::fromServerRequest($request);
        } catch (ValidationException $e) {
            throw new UnprocessableContentException(
                array_map(
                    fn(array $errors) => array_map(
                        fn(ValidationError $error) => $this->translator->translate($error->key, $error->replacements),
                        $errors
                    ),
                    $e->getErrors(),
                )
            );
        }
    }
}