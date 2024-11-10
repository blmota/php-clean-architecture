<?php

declare(strict_types=1);

namespace Source\Domain\Entities;

use Source\Domain\Traits\HydrateTrait;
use Source\Domain\ValueObjects\Email;
use Source\Domain\ValueObjects\Password;

final class User extends GeneralEntity
{
    use HydrateTrait;

    private string $firstName;
    private string $lastName;
    private string $type;
    private Email $email;
    private ?Password $password;
    private int $level;
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
