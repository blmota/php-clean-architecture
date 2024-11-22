<?php

declare(strict_types=1);

namespace Source\Domain\Entities;

use DateTime;
use DateTimeInterface;
use Source\Domain\Attributes\Column;
use Source\Domain\ValueObjects\Cnpj;

final class UserCompany extends GeneralEntity
{
    #[Column(name: "user_id", type: "int")]
    private int $userId;

    #[Column(name: "document", type: "string")]
    private Cnpj $document;

    #[Column(name: "date_start", type: "string", customConverter: "DateTime")]
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
