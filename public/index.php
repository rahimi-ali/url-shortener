<?php

use App\ServiceProviders\AppServiceProvider;
use App\ServiceProviders\CachingServiceProvider;
use App\ServiceProviders\LoggingServiceProvider;
use App\ServiceProviders\PersistenceServiceProvider;
use Infrastructure\Config\Contracts\ConfigManagerInterface;
use Infrastructure\Config\InMemoryConfigManager;
use Infrastructure\Config\JsonConfigLoader;
use Infrastructure\Di\Contracts\ServiceContainerInterface;
use Infrastructure\Di\ServiceContainer;
use Infrastructure\Http\Router;
use Infrastructure\Http\RouteRegistrar;
use Infrastructure\Http\SapiEmitterAdapter;
use Laminas\Diactoros\ServerRequestFactory;

require_once __DIR__ . '/../vendor/autoload.php';

// Load Config
$configLoader = new JsonConfigLoader();
$config = $configLoader->loadFromDirectory(__DIR__ . '/../config');
$configManager = new InMemoryConfigManager();
$configManager->setMany($config);

// Setup DI
$serviceContainer = new ServiceContainer();
$serviceContainer->set(ServiceContainerInterface::class, $serviceContainer);
$serviceContainer->set(ConfigManagerInterface::class, $configManager);
(new LoggingServiceProvider())->register($serviceContainer);
(new PersistenceServiceProvider())->register($serviceContainer);
(new CachingServiceProvider())->register($serviceContainer);
(new AppServiceProvider())->register($serviceContainer);

// Setup router
$routeRegistrar = new RouteRegistrar();
(require_once __DIR__ . '/../src/App/routes.php')($routeRegistrar);
$router = new Router($serviceContainer, $routeRegistrar, new SapiEmitterAdapter(), true, true);

// Route!
$request = ServerRequestFactory::fromGlobals();
$router->run($request);
