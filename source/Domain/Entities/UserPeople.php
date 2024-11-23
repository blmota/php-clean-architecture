<?php

declare(strict_types=1);

namespace Source\Domain\Entities;

use DateTime;
use DateTimeInterface;
use Source\Domain\Attributes\Column;
use Source\Domain\Attributes\Table;
use Source\Domain\ValueObjects\Cpf;

#[Table(name: "user_people")]
final class UserPeople extends GeneralEntity
{
    #[Column(name: "user_id", type: "int")]
    private int $userId;

    #[Column(name: "document", type: "string")]
    private Cpf $document;

    #[Column(name: "date_birth", type: "string", customConverter: "DateTime")]
    private DateTimeInterface $dateBirth;

    #[Column(name: "genre", type: "string")]
    private string $genre;

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getDocument(): Cpf
    {
        return $this->document;
    }

    public function setDocument(string $document): void
    {
        $this->document = new Cpf($document);
    }

    public function getDateBirth(): DateTimeInterface
    {
        return $this->dateBirth;
    }

    public function setDateBirth(string $dateBirth): void
    {
        $this->dateBirth = new DateTime($dateBirth);
    }

    public function getGenre(): string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): void
    {
        $this->genre = $genre;
    }
}
