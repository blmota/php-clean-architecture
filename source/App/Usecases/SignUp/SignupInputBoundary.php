<?php

declare(strict_types=1);

namespace Source\App\Usecases\SignUp;

use Source\Domain\ValueObjects\Email;
use Source\Domain\ValueObjects\Password;

final class SignupInputBoundary
{
    private string $firstName;
    private string $lastName;
    private string $type;
    private Email $email;
    private Password $password;
    private ?int $level;

    public function __construct(
        string $firstName,
        string $lastName,
        string $type,
        Email $email,
        Password $password,
        int $level = 1
    )
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->type = $type;
        $this->email = $email;
        $this->password = $password;
        $this->level = $level;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getEmail(): string
    {
        return (string) $this->email;
    }

    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function getLevel(): int
    {
        return $this->level;
    }
}