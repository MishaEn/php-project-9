<?php

declare(strict_types=1);

namespace App\Application\Actions\Url;

use App\Application\Actions\Action;
use App\Domain\Url\UrlRepository;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use Slim\Flash\Messages;
use Slim\Views\Twig;

abstract class UrlAction extends Action
{
    protected UrlRepository $urlRepository;

    public function __construct(
        LoggerInterface $logger,
        UrlRepository $urlRepository,
        Twig $view,
        Messages $flash,
        Client $guzzle
    ) {
        parent::__construct($logger, $view, $flash, $guzzle);
        $this->urlRepository = $urlRepository;
    }
}
