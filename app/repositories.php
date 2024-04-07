<?php

declare(strict_types=1);

use App\Domain\User\UserRepository;
use App\Domain\Url\UrlRepository;
use App\Infrastructure\Persistence\Url\InMemoryUrlRepository;
use App\Infrastructure\Persistence\User\InMemoryUserRepository;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        UserRepository::class => \DI\autowire(InMemoryUserRepository::class),
        UrlRepository::class => \DI\autowire(InMemoryUrlRepository::class),
    ]);
};
