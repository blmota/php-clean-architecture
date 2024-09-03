<?php

declare(strict_types=1);

namespace Source\App\Usecases\Wallet\Balance;

final class BalanceOutputBoundary
{
    private float $balance;

    public function __construct(array $data)
    {
        $this->balance = $data["balance"] ?? 0;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }
}