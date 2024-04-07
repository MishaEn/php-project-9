<?php

namespace App\Domain\Url;

use Carbon\Carbon;

/**
 *
 */
interface UrlRepository
{
    /**
     * @return Url[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return Url
     */
    public function findUrlOfId(int $id): Url;

    /**
     * @param string $name
     * @param string $created_at
     * @return int
     */
    public function add(string $name, string $created_at): int;
    public function addCheck(
        int $url_id,
        string $status,
        ?string $tag,
        ?string $title,
        ?string $description,
        string $created_at
    ): void;
    public function getLastCheckStatusCode(int $url_id): ?string;
    public function findChecksOfUrlId(int $url_id): array;
}
