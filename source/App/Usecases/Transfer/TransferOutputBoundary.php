<?php

declare(strict_types=1);

namespace Source\App\Usecases\Transfer;

final class TransferOutputBoundary
{
    private string $userNameFrom;
    private string $userNameTo;
    private string $value;
    private string $createdAt;

    public function __construct(array $data)
    {
        $this->userNameFrom = $data["userNameFrom"] ?? '';
        $this->userNameTo = $data["userNameTo"] ?? '';
        $this->value = $data["value"] ?? '';
        $this->createdAt = $data["createdAt"] ?? '';
    }

    public function getUserNameFrom(): string
    {
        return $this->userNameFrom;
    }

    public function getUserNameTo(): string
    {
        return $this->userNameTo;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }
}