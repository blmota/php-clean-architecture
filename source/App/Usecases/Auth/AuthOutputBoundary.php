<?php

declare(strict_types=1);

namespace Source\App\Usecases\Auth;

final class AuthOutputBoundary
{
    private int $id;
    private string $firstName;
    private string $lastName;
    private string $email;
    private int $status;
    private string $createdAt;
    private array $authenticate;

    public function __construct(array $data)
    {
        $this->id = $data["id"];
        $this->firstName = $data["first_name"];
        $this->lastName = $data["last_name"];
        $this->email = $data["email"];
        $this->status = $data["status"];
        $this->createdAt = $data["created_at"];
        $this->authenticate = $data["authenticate"];
    }

    public function getId(): int{
        return $this->id;
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

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getAuthenticate(): array
    {
        return $this->authenticate;
    }

    public function getDataArray(): array
    {
        return [
            "id" => $this->getId(),
            "first_name" => $this->getFirstName(),
            "last_name" => $this->getLastName(),
            "email" => $this->getEmail(),
            "status" => $this->getStatus(),
            "created_at" => $this->getCreatedAt(),
            "authenticate" => $this->getAuthenticate()
        ];
    }
}