<?php

declare(strict_types=1);

namespace Source\Domain\Entities;

use Source\Domain\Attributes\Column;
use Source\Domain\Attributes\Table;
use Source\Domain\Traits\HydrateTrait;

#[Table(name: "transfer")]
final class Transfer extends GeneralEntity
{
    use HydrateTrait;

    #[Column(name: "user_from", type: "int")]
    private int $userFrom;

    #[Column(name: "user_to", type: "int")]
    private int $userTo;

    #[Column(name: "value", type: "float")]
    private float $value;

    public function getUserFrom(): int
    {
        return $this->userFrom;
    }

    public function setUserFrom(int $userFrom): void
    {
        $this->userFrom = $userFrom;
    }

    public function getUserTo(): int
    {
        return $this->userTo;
    }

    public function setUserTo(int $userTo): void
    {
        $this->userTo = $userTo;
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
