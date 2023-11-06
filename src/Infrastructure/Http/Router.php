<?php

namespace Infrastructure\Http;

use Infrastructure\Di\Contracts\ServiceContainerInterface;
use Infrastructure\Http\Contracts\EmitterInterface;
use Infrastructure\Http\Contracts\HttpExceptionInterface;
use Infrastructure\Http\Contracts\HttpMethod;
use Infrastructure\Http\Contracts\RequestInterface;
use Infrastructure\Http\Contracts\RouteRegistrarInterface;
use Infrastructure\Http\Exceptions\HttpNotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Throwable;
use Laminas\Diactoros\Response\JsonResponse;

class Router
{
    private readonly LoggerInterface $logger;

    public function __construct(
        private readonly ServiceContainerInterface $serviceContainer,
        private readonly RouteRegistrarInterface $routeRegistrar,
        private readonly EmitterInterface $emitter,
        private readonly bool $logErrors = true,
        private readonly bool $debug = true,
    ) {
        $this->logger = $this->serviceContainer->get(LoggerInterface::class);
    }

    public function run(ServerRequestInterface $request): void
    {
        try {
            foreach ($this->routeRegistrar->getRoutes() as $route) {
                $params = $route->match($request->getUri(), HttpMethod::from($request->getMethod()));

                if ($params === false) {
                    continue;
                }

                $request = new Request($request, $params);

                $middlewareStack = $route->getMiddleware();
                $handler = $route->getHandler();

                $next = function (RequestInterface $request) use ($middlewareStack, $handler, &$next) {
                    static $depth = 0;

                    if (isset($middlewareStack[$depth])) {
                        $middleware = $middlewareStack[$depth];
                        $depth++;
                        return $this->serviceContainer->call($middleware, ['request' => $request, 'next' => $next]);
                    }

                    return $this->serviceContainer->call($handler, ['request' => $request]);
                };

                /** @var ResponseInterface $response */
                $response = $next($request);

                break;
            }

            if (!isset($response)) {
                throw new HttpNotFoundException();
            }
        } catch (HttpExceptionInterface $exception) {
            $request = new Request($request, []);
            $response = $exception->render($request);
        } catch (Throwable $exception) {
            if ($this->logErrors) {
                $this->logger->error($exception->getMessage(), $exception->getTrace());
            }

            $data = [
                'code' => 500,
                'message' => 'something went wrong'
            ];

            if ($this->debug) {
                $data['message'] = $exception->getMessage();
                $data['trace'] = $exception->getTrace();
            }

            $response = new JsonResponse($data, 500);
        }

        $this->emitter->emit($response, $request->getMethod() === 'HEAD');
    }
}