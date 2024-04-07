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
        $result = '';

        $urlData = $this->request->getParsedBody();

        $urlName = $urlData['url']['name'];

        preg_match('/^(https?:\/\/[^\/]+)/i', $urlName, $matches);

        if (count($matches) === 0) {
            $this->flash->addMessage('error', 'Некорректный URL');

            return $this->response->withHeader('Location', "/")->withStatus(302);
        }

        $urlId = $this->urlRepository->add($matches[1], Carbon::now());

        $this->flash->addMessage('info', 'Страница успешно добавлена');

        return $this->response->withHeader('Location', "/urls/{$urlId}")->withStatus(302);
    }
}
