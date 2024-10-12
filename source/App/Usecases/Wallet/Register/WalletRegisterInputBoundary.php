<?php

declare(strict_types=1);

namespace Source\App\Usecases\Wallet\Register;

final class WalletRegisterInputBoundary
{
    private int $userId;
    private float $value;

    public function __construct(array $data)
    {
        $this->userId = $data["user_id"];
        $this->value = $data["value"];
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
