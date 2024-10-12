<?php

declare(strict_types=1);

namespace Source\App\Usecases\UserData;

final class UserDataOutputBoundary
{
    private int $id;
    private string $type;
    private string $firstName;
    private string $lastName;
    private string $email;
    private int $level;
    private int $status;
    private string $createdAt;

    public function __construct(array $data)
    {
        $this->id = $data["id"];
        $this->type = $data["type"];
        $this->firstName = $data["first_name"];
        $this->lastName = $data["last_name"];
        $this->email = $data["email"];
        $this->level = $data["level"];
        $this->status = $data["status"];
        $this->createdAt = $data["created_at"];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getDataArray(): array
    {
        return [
            "id" => $this->getId(),
            "type" => $this->getType(),
            "first_name" => $this->getFirstName(),
            "last_name" => $this->getLastName(),
            "email" => $this->getEmail(),
            "level" => $this->getLevel(),
            "status" => $this->getStatus(),
            "created_at" => $this->getCreatedAt()
        ];
    }
}
