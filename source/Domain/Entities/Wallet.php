<?php

declare(strict_types=1);

namespace Source\Domain\Entities;

final class Wallet extends GeneralEntity
{
    private int $userId;
    private float $value;

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function setValue(float $value): void
    {
        $this->value = $value;
    }
}