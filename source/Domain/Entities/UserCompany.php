<?php

declare(strict_types=1);

namespace Source\Domain\Entities;

use DateTime;
use DateTimeInterface;
use DomainException;
use Source\Domain\ValueObjects\Cnpj;

final class UserCompany extends GeneralEntity
{
    private int $userId;
    private Cnpj $document;
    private DateTimeInterface $dateStart;

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getDocument(): Cnpj
    {
        return $this->document;
    }

    public function setDocument(string $document): void
    {
        $this->document = new Cnpj($document);
    }

    public function getDateStart(): DateTimeInterface
    {
        return $this->dateStart;
    }

    public function setDateStart(string $dateStart): void
    {
        $this->dateStart = new DateTime($dateStart);
    }
}
