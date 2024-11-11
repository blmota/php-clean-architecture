<?php

declare(strict_types=1);

namespace Source\Domain\Repositories;

use Source\Domain\Entities\Wallet;

interface WalletRepository
{
    public function store(int $userId): ?Wallet;
    public function balance(int $userId): float;
    public function register(Wallet $wallet): array;
    public function addValue(int $userId, float $value): Wallet;
    public function subValue(int $userId, float $value): Wallet;
}
