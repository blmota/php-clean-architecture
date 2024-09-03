<?php

declare(strict_types=1);

namespace Source\App\Usecases\Transfer;

use Source\Domain\Entities\User;

final class TransferInputBoundary
{
    private User $userFrom;
    private User $userTo;
    private float $value;

    public function __construct(int $userFrom, int $userTo, float $value)
    {
        $this->userFrom = $userFrom;
        $this->userTo = $userTo;
        $this->value = $value;
    }

    public function getUserFrom(): User
    {
        return $this->userFrom;
    }

    public function getUserTo(): User
    {
        return $this->userTo;
    }

    public function getValue(): float
    {
        return $this->value;
    }
}