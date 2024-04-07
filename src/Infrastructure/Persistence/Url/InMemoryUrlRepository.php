<?php

namespace App\Infrastructure\Persistence\Url;

use App\Domain\Url\Url;
use App\Domain\Url\UrlRepository;
use PDO;

class InMemoryUrlRepository implements UrlRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * @inheritDoc
     */
    public function findAll(): array
    {
        $sql = /** @lang PostgreSQL */
            <<<SQL
            SELECT * FROM urls
SQL;

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * @inheritDoc
     */
    public function findUrlOfId(int $id): Url
    {
        $sql = /** @lang PostgreSQL */
            <<<SQL
            SELECT * FROM urls WHERE id = :id
SQL;
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();

        $url = $stmt->fetch();

        return new Url($url['id'], $url['name'], $url['created_at']);
    }

    public function add(string $name, string $created_at): int
    {
        $sql = /** @lang PostgreSQL */
            <<<SQL
            INSERT INTO urls (name, created_at) VALUES (:name, :created_at)
SQL;
        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(":name", $name);
        $stmt->bindValue(":created_at", $created_at);

        $stmt->execute();

        return (int) $this->db->lastInsertId();
    }

    public function addCheck(
        int $url_id,
        string $status,
        ?string $tag,
        ?string $title,
        ?string $description,
        string $created_at
    ): void {
        $sql = /** @lang PostgreSQL */
            <<<SQL
            INSERT INTO url_checks (url_id, status_code, h1, title, description, created_at) 
            VALUES (:url_id, :status_code, :h1, :title, :description, :created_at)
SQL;
        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(":url_id", $url_id);
        $stmt->bindValue(":status_code", $status);
        $stmt->bindValue(":h1", $tag);
        $stmt->bindValue(":title", $title);
        $stmt->bindValue(":description", $description);
        $stmt->bindValue(":created_at", $created_at);

        $stmt->execute();
    }

    public function findChecksOfUrlId(int $url_id): array
    {
        $sql = /** @lang PostgreSQL */
            <<<SQL
            SELECT * FROM url_checks WHERE url_id = :url_id
SQL;

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":url_id", $url_id);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getLastCheckStatusCode(int $url_id): ?string
    {
        $sql = /** @lang PostgreSQL */
            <<<SQL
            SELECT status_code FROM url_checks WHERE url_id = :url_id ORDER BY id DESC LIMIT 1
SQL;

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":url_id", $url_id);
        $stmt->execute();
        $result = $stmt->fetch();

        return $result['status_code'] ?? null;
    }
}
