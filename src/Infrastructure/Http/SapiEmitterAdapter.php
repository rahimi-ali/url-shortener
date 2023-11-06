<?php

namespace Infrastructure\Http;

use HttpSoft\Emitter\SapiEmitter;
use Infrastructure\Http\Contracts\EmitterInterface;
use Psr\Http\Message\ResponseInterface;

class SapiEmitterAdapter implements EmitterInterface
{
    private readonly SapiEmitter $sapiEmitter;

    public function __construct(int|null $bufferLength = null)
    {
        $this->sapiEmitter = new SapiEmitter($bufferLength);
    }

    public function emit(ResponseInterface $response, bool $withoutBody = false): void
    {
        $this->sapiEmitter->emit($response, $withoutBody);
    }
}