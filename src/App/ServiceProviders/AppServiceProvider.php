<?php

namespace App\ServiceProviders;

use App\Repositories\LinkRepository;
use App\Repositories\LinkRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;
use App\Services\AuthService;
use Infrastructure\Config\Contracts\ConfigManagerInterface;
use Infrastructure\Di\Contracts\ServiceContainerInterface;
use Infrastructure\Di\Contracts\ServiceProvider;

class AppServiceProvider implements ServiceProvider
{
    public function register(ServiceContainerInterface $serviceContainer): void
    {
        $this->registerRepositories($serviceContainer);
        $this->registerServices($serviceContainer);
    }

    private function registerRepositories(ServiceContainerInterface $serviceContainer): void
    {
        $serviceContainer->set(
            UserRepositoryInterface::class,
            fn() => $serviceContainer->make(UserRepository::class, [])
        );

        $serviceContainer->set(
            LinkRepositoryInterface::class,
            fn() => $serviceContainer->make(LinkRepository::class, [])
        );
    }

    private function registerServices(ServiceContainerInterface $serviceContainer): void
    {
        /** @var ConfigManagerInterface $configManager */
        $configManager = $serviceContainer->get(ConfigManagerInterface::class);

        $authService = $serviceContainer->make(
            AuthService::class,
            [
                'publicKey' => $configManager->get('auth.publicKey'),
                'privateKey' => $configManager->get('auth.privateKey'),
                'tokenTtl' => $configManager->get('auth.tokenTtl'),
            ]
        );
        $serviceContainer->set(AuthService::class, $authService);
    }
}