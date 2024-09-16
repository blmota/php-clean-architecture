<?php

declare(strict_types=1);

namespace Source\Domain\Repositories;

use Source\Domain\Entities\User;

interface SignupRepository
{
    public function execute(User $newUser): array;
}