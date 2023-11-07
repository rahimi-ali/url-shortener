<?php

namespace App\Http\Responses;

use App\Entities\Link;
use Laminas\Diactoros\Response\JsonResponse;

class ShortLinkListResponse extends JsonResponse
{
    /** @param Link[] $links */
    public function __construct(array $links)
    {
        parent::__construct(array_map(
            fn (Link $link) => [
                'id' => $link->getId(),
                'shortLink' => $link->getShortLink(),
                'uri' => $link->getUri(),
            ],
            $links
        ));
    }
}