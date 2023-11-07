<?php

namespace App\ServiceProviders;

use Infrastructure\Di\Contracts\ServiceContainerInterface;
use Infrastructure\Di\Contracts\ServiceProvider;
use Infrastructure\Localization\Contracts\TranslatorInterface;
use Infrastructure\Localization\Translator;

class TranslationsServiceProvider implements ServiceProvider
{
    public function register(ServiceContainerInterface $serviceContainer): void
    {
        $serviceContainer->set(
            TranslatorInterface::class,
            Translator::loadFromDirectory(__DIR__ . '/../../../translations')
        );
    }
}