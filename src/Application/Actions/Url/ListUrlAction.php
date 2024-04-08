<?php

declare(strict_types=1);

namespace App\Application\Actions\Url;

use Psr\Http\Message\ResponseInterface as Response;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ListUrlAction extends UrlAction
{
    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    protected function action(): Response
    {
        if ($this->flash->getFirstMessage('error') === 'Некорректный URL') {
            return $this->respondTemplate('index.twig', [
                'message' => $this->flash->getFirstMessage('error')
            ]);
        }

        $urls = $this->urlRepository->findAll();

        foreach ($urls as $key => $url) {
            $urls[$key]['status'] = $this->urlRepository->getLastCheckStatusCode($url['id']);  /** @phpstan-ignore-line */
        }

        $this->logger->info("Urls list was viewed.");

        return $this->respondTemplate('list_urls.twig', ['urls' => $urls]);
    }
}
