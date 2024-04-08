<?php

namespace App\Application\Actions\Url;

use Psr\Http\Message\ResponseInterface as Response;
use Carbon\Carbon;

/**
 *
 */
class AddUrlAction extends UrlAction
{
    /**
     * @inheritDoc
     */
    protected function action(): Response
    {
        $urlData = $this->request->getParsedBody();
        $urlName = $urlData['url']['name'];
        /** @phpstan-ignore-line */
        preg_match('/^(https?:\/\/[^\/]+)/i', $urlName, $matches);

        if (count($matches) === 0) {
            return $this->respondTemplate('index.twig', [
                'message' => 'Некорректный URL'
            ], 422);
        }

        $findUrl = $this->urlRepository->findUrlOfName($matches[1]);

        if ($findUrl !== null) {
            $this->flash->addMessage('info', 'Страница уже существует');

            return $this->response->withHeader('Location', "/urls/{$findUrl->getId()}")->withStatus(302);
        }

        $urlId = $this->urlRepository->add($matches[1], Carbon::now());

        $this->flash->addMessage('info', 'Страница успешно добавлена');

        return $this->response->withHeader('Location', "/urls/{$urlId}")->withStatus(302);
    }
}
