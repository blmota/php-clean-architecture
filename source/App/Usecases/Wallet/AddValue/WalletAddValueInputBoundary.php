<?php

declare(strict_types=1);

namespace Source\App\Usecases\Wallet\AddValue;

final class WalletAddValueInputBoundary
{
    private int $userId;
    private float $value;

    public function __construct(int $userId, float $value)
    {
        $this->userId = $userId;
        $this->value = $value;
    }


    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getValue(): float
    {
        return $this->value;
    }
}
