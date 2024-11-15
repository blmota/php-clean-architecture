<?php

declare(strict_types=1);

namespace Source\Domain\Entities;

use Source\Domain\Traits\HydrateTrait;

final class Transfer extends GeneralEntity
{
    use HydrateTrait;

    private int $userFrom;
    private int $userTo;
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
