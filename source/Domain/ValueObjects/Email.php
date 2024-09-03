<?php

declare(strict_types=1);

namespace Source\Domain\ValueObjects;

use DomainException;

class Email
{
    private string $email;

    public function __construct(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new DomainException("Informe um e-mail válido!");
        }

        $this->email = $email;
    }

    public function __toString(): string
    {
        return $this->email;
    }
}