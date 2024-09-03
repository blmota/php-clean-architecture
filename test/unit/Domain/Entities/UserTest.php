<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Source\Domain\Entities\User;
use Source\Domain\Exceptions\ValueObjects\EmailInvalidException;
use Source\Domain\Exceptions\ValueObjects\PasswordInvalidDigitsNumber;

class UserTest extends TestCase
{
    public function testUserInvalidEmail()
    {
        // Espera que uma DomainException seja lançada
        $this->expectException(EmailInvalidException::class);
        $this->expectExceptionMessage('Informe um e-mail válido!');

        $user = new User;
        $user->setFirstName("Hector");
        $user->setLastName("Bonilla");
        $user->setType("F");
        $user->setEmail("hector.bonilla_bluware.com.br");
        $user->setStatus(0);
    }

    public function testPasswordInvalidMinDigitNumber()
    {
        $this->expectException(PasswordInvalidDigitsNumber::class);

        $user = new User;
        $user->setFirstName("Hector");
        $user->setLastName("Bonilla");
        $user->setType("F");
        $user->setEmail("hector.bonilla@bluware.com.br");
        $user->setPassword("1234567");
        $user->setStatus(0);
    }

    public function testPasswordInvalidMaxDigitNumber()
    {
        $this->expectException(PasswordInvalidDigitsNumber::class);

        $user = new User;
        $user->setFirstName("Hector");
        $user->setLastName("Bonilla");
        $user->setType("F");
        $user->setEmail("hector.bonilla@bluware.com.br");
        $user->setPassword("123456789012345678901");
        $user->setStatus(0);
    }
}