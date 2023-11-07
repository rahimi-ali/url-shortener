<?php

namespace App\Http\Responses;

use App\Entities\Link;
use Laminas\Diactoros\Response\JsonResponse;

class ShortLinkCreatedResponse extends JsonResponse
{
    public function __construct(Link $link)
    {
        parent::__construct([
            'id' => $link->getId(),
            'uri' => $link->getUri(),
            'shortLink' => $link->getShortLink(),
        ]);
    }
}