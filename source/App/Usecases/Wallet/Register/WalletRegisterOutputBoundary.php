<?php

declare(strict_types=1);

namespace Source\App\Usecases\Wallet\Register;

use DateTime;

final class WalletRegisterOutputBoundary
{
    private int $id;
    private int $userId;
    private float $value;
    private string $createdAt;

    public function __construct(array $data)
    {
        $this->id = $data["id"];
        $this->userId = $data["user_id"];
        $this->value = $data["value"];
        $this->createdAt = $data["created_at"];
    }

    public function getDataArray(): array
    {
        return [
            "id" => $this->id,
            "user_id" => $this->userId,
            "value" => $this->value,
            "created_at" => (new DateTime($this->createdAt))->format("d/m/Y H\hi")
        ];
    }
}
