<?php

declare(strict_types=1);

namespace Source\App\Usecases\Auth;

use Source\Domain\ValueObjects\Email;
use Source\Domain\ValueObjects\Password;

final class AuthInputBoundary
{
    private Email $email;
    private Password $password;

    public function __construct(Email $email, Password $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public function getEmail(): string
    {
        return (string) $this->email;
    }

    public function getPassword(): string
    {
        return (string) $this->password;
    }
}
