<?php

declare(strict_types=1);

namespace Source\Domain\Entities;

use DateTime;
use DateTimeInterface;
use Source\Domain\ValueObjects\Cpf;

final class UserPeople extends GeneralEntity
{
    private int $userId;
    private Cpf $document;
    private DateTimeInterface $dateBirth;
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
