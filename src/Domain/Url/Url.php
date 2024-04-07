<?php

namespace App\Domain\Url;

use JsonSerializable;

class Url implements JsonSerializable
{
    private ?int $id;

    private string $name;

    private string $created_at;

    public function __construct(?int $id, string $name, string $created_at)
    {
        $this->id = $id;
        $this->name = $name;
        $this->created_at = $created_at;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }
    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'created_at' => $this->created_at,
        ];
    }
}
