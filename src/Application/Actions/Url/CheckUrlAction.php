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

        $response = $this->guzzle->request('GET', $url->getName());  /** @phpstan-ignore-line */
        $body = $response->getBody()->getContents();

        $document = new Document();
        $document->loadHtml($body);

        $this->flash->addMessage('info', 'Страница успешно проверена');

        $status = $response->getStatusCode();
        $params = [];

        $params['h1'] = $document->has('h1')
            ? $document->first('h1')->text()  /** @phpstan-ignore-line */
            : null;

        $params['title'] = $document->has('title')
            ? $document->first('title')->text()  /** @phpstan-ignore-line */
            : null;

        $params['description'] = $document->has('meta[name="description"]')
            ? $document->first('meta[name="description"]')->attr('content')  /** @phpstan-ignore-line */
            : null;

        $this->urlRepository->addCheck(
            $urlId,
            $status,  /** @phpstan-ignore-line */
            $params['h1'],
            $params['title'],
            $params['description'],
            Carbon::now()
        );

        return $this->response->withHeader('Location', "/urls/{$urlId}")->withStatus(302);
    }
}
