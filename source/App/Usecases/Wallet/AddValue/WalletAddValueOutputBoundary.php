<?php

declare(strict_types=1);

namespace Source\App\Usecases\Wallet\AddValue;

use Source\Domain\Entities\Wallet;

final class WalletAddValueOutputBoundary
{
    private int $id;
    private int $userId;
    private float $value;
    private string $createdAt;

    public function __construct(Wallet $data)
    {
        $this->id = $data->getId();
        $this->userId = $data->getUserId();
        $this->value = $data->getValue();
        $this->createdAt = $data->getCreatedAt();
    }

    public function getDataArray(): array
    {
        return (array) $this;
    }
}
