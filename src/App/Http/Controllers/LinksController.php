<?php

namespace App\Http\Controllers;

use App\Entities\Link;
use App\Exceptions\FailedToGenerateUniqueShortLink;
use App\Exceptions\ForbiddenException;
use App\Exceptions\UnprocessableContentException;
use App\Http\Requests\CreateLinkRequest;
use App\Http\Responses\ShortLinkCreatedResponse;
use App\Http\Responses\ShortLinkListResponse;
use App\Repositories\LinkRepositoryInterface;
use App\Services\AuthService;
use App\Services\Validator;
use Infrastructure\Cache\Contracts\CacheServiceInterface;
use Infrastructure\Config\Contracts\ConfigManagerInterface;
use Infrastructure\Http\Contracts\RequestInterface;
use Infrastructure\Http\Exceptions\HttpNotFoundException;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Laminas\Diactoros\Response\JsonResponse;

class LinksController
{
    public function __construct(
        private readonly LinkRepositoryInterface $linkRepository,
        private readonly AuthService $authService,
        private readonly Validator $validator,
        private readonly ConfigManagerInterface $configManager,
        private readonly CacheServiceInterface $cacheService,
    ) {
    }

    /**
     * @throws UnprocessableContentException
     * @throws FailedToGenerateUniqueShortLink
     */
    public function store(RequestInterface $request): ShortLinkCreatedResponse
    {
        $data = $this->validator->validate(CreateLinkRequest::class, $request);

        $user = $this->authService->getAuthenticatedUser();

        $shortLink = $this->generateShortLink();

        $link = new Link();
        $link->setUserId($user->getId());
        $link->setUri($data->getUri());
        $link->setShortLink($shortLink);
        $this->linkRepository->save($link);

        return new ShortLinkCreatedResponse($link);
    }

    public function index(): ShortLinkListResponse
    {
        $user = $this->authService->getAuthenticatedUser();

        $links = $this->linkRepository->getByUserId($user->getId());

        return new ShortLinkListResponse($links);
    }

    /**
     * @throws HttpNotFoundException
     * @throws ForbiddenException
     */
    public function delete(RequestInterface $request): ResponseInterface
    {
        $id = $request->routeParam('id');
        if ($id === null) {
            throw new HttpNotFoundException();
        }

        $link = $this->linkRepository->findById($id);
        if ($link === null) {
            throw new HttpNotFoundException();
        }

        $user = $this->authService->getAuthenticatedUser();

        if ($link->getUserId() !== $user->getId()) {
            throw new ForbiddenException();
        }

        $this->linkRepository->deleteById($link->getId());

        return new JsonResponse(null, 204);
    }

    /**
     * @throws HttpNotFoundException
     */
    public function redirect(RequestInterface $request): RedirectResponse
    {
        $shortLink = $request->routeParam('shortLink');
        if ($shortLink === null) {
            throw new HttpNotFoundException();
        }

        $uri = $this->cacheService->get($shortLink);

        if ($uri === false) {
            $link = $this->linkRepository->findByShortLink($shortLink);
            if ($link === null) {
                throw new HttpNotFoundException();
            }

            $uri = $link->getUri();
            $this->cacheService->remember($shortLink, $uri, $this->configManager->get('shortener.cacheTTL'));
        }

        return new RedirectResponse($uri);
    }


    /** @throws FailedToGenerateUniqueShortLink */
    private function generateShortLink(): string
    {
        $length = $this->configManager->get('shortener.length');
        $retries = $this->configManager->get('shortener.retries');
        $characters = $this->configManager->get('shortener.characters');

        $charactersLength = strlen($characters);

        for ($i = $retries; $i > 0; $i--) {
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[random_int(0, $charactersLength - 1)];
            }

            if ($this->linkRepository->findByShortLink($randomString) === null) {
                return $randomString;
            }
        }

        throw new FailedToGenerateUniqueShortLink();
    }
}