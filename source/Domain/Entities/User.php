<?php

declare(strict_types=1);

namespace Source\Domain\Entities;

use Source\Domain\ValueObjects\Email;
use Source\Domain\ValueObjects\Password;

final class User extends GeneralEntity
{
    private string $firstName;
    private string $lastName;
    private string $type;
    private Email $email;
    private Password $password;
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
        $this->email = $email;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getStatus(): int
    {
        return $this->status;
    }
}