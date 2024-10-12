<?php

declare(strict_types=1);

namespace Source\Infra\Repositories;

use DateTime;
use Exception;
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

    public function register(EntitiesWallet $wallet): array
    {
        $userWalletExists = $this->find("user_id = :user", "user={$wallet->getUserId()}")->count();
        
        if ($userWalletExists) {
            throw new Exception("Você pode ter apenas uma carteira.");
        }

        $this->user_id = $wallet->getUserId();
        $this->value = (int) $wallet->getValue();

        if (!$this->save()) {
            throw new Exception($this->message()->getText());
        }

        return (array) $this->data();
    }

    public function addValue(int $userId, float $value): EntitiesWallet
    {
        $wallet = $this->find("userId = :user", "user={$userId}")->fetch();

        if (empty($wallet)) {
            throw new Exception("A carteira não existe ou foi removida recentemente.");
        }

        $wallet->value += $value;

        if (!$wallet->save()) {
            throw new Exception($wallet->message()->getText());
        }

        $walletEntity = new EntitiesWallet();
        $walletEntity->setId($wallet->id);
        $walletEntity->setUserId($wallet->user_id);
        $walletEntity->setValue($wallet->value);
        $walletEntity->setCreatedAt((new DateTime($wallet->createdAt)));
        $walletEntity->setUpdatedAt((new DateTime($wallet->updatedAt)));

        return $walletEntity;
    }

    public function subValue(int $userId, float $value): EntitiesWallet
    {
        $wallet = $this->find("userId = :user", "user={$userId}")->fetch();

        if (empty($wallet)) {
            throw new Exception("A carteira não existe ou foi removida recentemente.");
        }

        if ($wallet->value < $value) {
            throw new Exception("A carteira não possui saldo suficiente para realizar a transferência.");
        }

        $wallet->value -= $value;

        if (!$wallet->save()) {
            throw new Exception($wallet->message()->getText());
        }

        $walletEntity = new EntitiesWallet();
        $walletEntity->setId($wallet->id);
        $walletEntity->setUserId($wallet->user_id);
        $walletEntity->setValue($wallet->value);
        $walletEntity->setCreatedAt((new DateTime($wallet->createdAt)));
        $walletEntity->setUpdatedAt((new DateTime($wallet->updatedAt)));

        return $walletEntity;
    }
}