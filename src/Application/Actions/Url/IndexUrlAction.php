<?php

declare(strict_types=1);

namespace App\Application\Actions\Url;

use Psr\Http\Message\ResponseInterface as Response;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class IndexUrlAction extends UrlAction
{
    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    protected function action(): Response
    {
        return $this->respondTemplate('index.twig', [
            'message' => $this->flash->getFirstMessage('error')
        ]);
    }
}
