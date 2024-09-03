<?php

declare(strict_types=1);

namespace Source\Infra\Repositories;

use DateTime;
use Source\Domain\Entities\Wallet as EntitiesWallet;
use Source\Domain\Repositories\WalletRepository;
use Source\Infra\Database\MariaDB\PdoRepository;

final class Wallet extends PdoRepository implements WalletRepository
{
    public function __construct()
    {
        parent::__construct("wallet", ["id"], ["user_id"]);
    }

    public function store(int $userId): ?EntitiesWallet
    {
        $userWallet = $this->find("user_id = :user", "user={$userId}")->fetch();

        if (empty($userWallet)) {
            return null;
        }

        $wallet = new EntitiesWallet();
        $wallet->setId($userWallet->id);
        $wallet->setUserId($userWallet->user_id);
        $wallet->setValue($userWallet->value);
        $wallet->setCreatedAt((new DateTime($userWallet->createdAt)));
        $wallet->setUpdatedAt((new DateTime($userWallet->updatedAt)));

        return $wallet;
    }

    public function balance(int $userId): float
    {
        $wallet = $this->store($userId);
        $balance = (!empty($wallet) ? $wallet->getValue() : 0);

        return $balance;
    }
}