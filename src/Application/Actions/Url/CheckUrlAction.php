<?php

namespace App\Application\Actions\Url;

use Carbon\Carbon;
use DiDom\Document;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface as Response;

class CheckUrlAction extends UrlAction
{
    /**
     * @inheritDoc
     * @throws GuzzleException
     */
    protected function action(): Response
    {
        $urlId = $this->request->getAttribute('id');
        $url = $this->urlRepository->findUrlOfId((int) $urlId);

        $response = $this->guzzle->request('GET', $url->getName());
        $document = new Document($url->getName(), true);

        $this->flash->addMessage('info', 'Страница успешно проверена');

        $status = $response->getStatusCode();
        $params = [];

        $params['h1'] = $document->has('h1')
            ? $document->first('h1')->text()
            : null;

        $params['title'] = $document->has('title')
            ? $document->first('title')->text()
            : null;

        $params['description'] = $document->has('meta[name="description"]')
            ? $document->first('meta[name="description"]')->attr('content')
            : null;

        $this->urlRepository->addCheck(
            $urlId,
            $status,
            $params['h1'],
            $params['title'],
            $params['description'],
            Carbon::now()
        );

        return $this->response->withHeader('Location', "/urls/{$urlId}")->withStatus(302);
    }
}
