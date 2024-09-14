<?php

declare(strict_types=1);

namespace Source\Domain\Repositories;

use Source\Domain\Entities\User;

interface AuthRepository
{
    public function execute(string $email, string $password): Array;
}