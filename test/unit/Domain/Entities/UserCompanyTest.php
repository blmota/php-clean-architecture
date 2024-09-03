<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Source\Domain\Entities\UserCompany;
use Source\Domain\Exceptions\ValueObjects\CnpjInvalidException;

class UserCompanyTest extends TestCase
{
    public function testUserInvalidCnpj()
    {
        // Espera que uma DomainException seja lançada
        $this->expectException(CnpjInvalidException::class);
        $this->expectExceptionMessage('O cnpj é inválido.');

        $user = new UserCompany;
        $user->setDocument("88.888.888/8888-88"); // 00.000.000/0000-00
        $user->setDateStart("2000-01-10");
    }
}