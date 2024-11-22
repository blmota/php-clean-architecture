<?php

declare(strict_types=1);

namespace Source\Domain\Entities;

use Source\Domain\Attributes\Column;
use Source\Domain\Traits\HydrateTrait;

final class Wallet extends GeneralEntity
{
    use HydrateTrait;

    #[Column(name: "user_id", type: "int")]
    private int $userId;
    
    #[Column(name: "value", type: "float")]
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
