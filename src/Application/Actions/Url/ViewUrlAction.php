<?php

namespace App\Application\Actions\Url;

use Psr\Http\Message\ResponseInterface as Response;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ViewUrlAction extends UrlAction
{
    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    protected function action(): Response
    {
        $urlId = $this->request->getAttribute('id');

        $url = $this->urlRepository->findUrlOfId((int) $urlId);
        $checks = $this->urlRepository->findChecksOfUrlId((int) $urlId);

        return $this->respondTemplate('item_url.twig', [
            'id' => $url->getId(),  /** @phpstan-ignore-line */
            'name' => $url->getName(),  /** @phpstan-ignore-line */
            'created_at' => $url->getCreatedAt(),  /** @phpstan-ignore-line */
            'checks' => $checks,
            'message' => $this->flash->getFirstMessage('info')
        ]);
    }
}
