<?php

declare(strict_types=1);

namespace Source\Domain\Entities;

use DateTimeInterface;
use Source\Domain\Attributes\Column;

abstract class GeneralEntity
{
    #[Column(name: "id", type: "int")]
    private ?int $id;

    #[Column(name: "created_at", type: "string", customConverter: "DateTime")]
    private ?DateTimeInterface $createdAt;

    #[Column(name: "updated_at", type: "string", customConverter: "DateTime")]
    private ?DateTimeInterface $updatedAt;

    public function getId(): ?int
    {
        return $this->id ?? null;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt ?? null;
    }

    public function setCreatedAt(?DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt ?? null;
    }

    public function setUpdatedAt(?DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
