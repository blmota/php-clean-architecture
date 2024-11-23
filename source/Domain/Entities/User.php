<?php

declare(strict_types=1);

namespace Source\Domain\Entities;

use Source\Domain\Attributes\Column;
use Source\Domain\Attributes\Table;
use Source\Domain\Traits\HydrateTrait;
use Source\Domain\ValueObjects\Email;
use Source\Domain\ValueObjects\Password;

#[Table(name: "users")]
final class User extends GeneralEntity
{
    use HydrateTrait;

    #[Column(name: "first_name", type: "string")]
    private string $firstName;

    #[Column(name: "last_name", type: "string")]
    private string $lastName;

    #[Column(name: "type", type: "string")]
    private string $type;

    #[Column(name: "email", type: "string")]
    private Email $email;

    #[Column(name: "password", type: "string")]
    private ?Password $password;

    #[Column(name: "level", type: "int")]
    private int $level;

    #[Column(name: "status", type: "int")]
    private int $status;

    public function getFullName(): string
    {
        return "{$this->firstName} {$this->lastName}";
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = new Email($email);
    }

    public function getPassword(): Password
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = new Password($password);
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }
}
