<?php

declare(strict_types=1);

namespace Source\App\Usecases\Transfer;

final class TransferOutputBoundary
{
    private int $id;
    private string $userNameFrom;
    private string $userNameTo;
    private string $value;
    private string $createdAt;

    public function __construct(array $data)
    {
        $this->id = $data["id"];
        $this->userNameFrom = $data["userNameFrom"] ?? '';
        $this->userNameTo = $data["userNameTo"] ?? '';
        $this->value = $data["value"] ?? '';
        $this->createdAt = $data["createdAt"] ?? '';
    }

    public function getId(): int
    {
        return $this->id;
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

    public function getDataArray(): array
    {
        return [
            "id" => $this->id,
            "userNameFrom" => $this->userNameFrom,
            "userNameTo" => $this->userNameTo,
            "createdAt" => $this->createdAt
        ];
    }
}
