<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Source\Domain\Entities\UserPeople;
use Source\Domain\Exceptions\ValueObjects\CpfInvalidException;

class UserPeopleTest extends TestCase
{
    public function testUserInvalidCpf()
    {
        // Espera que uma DomainException seja lançada
        $this->expectException(CpfInvalidException::class);
        $this->expectExceptionMessage('O cpf é inválido.');

        $userPeople = new UserPeople;
        $userPeople->setDocument("888.888.888-88");
        $userPeople->setDateBirth("1991-05-18");
        $userPeople->setGenre("male");
    }
}