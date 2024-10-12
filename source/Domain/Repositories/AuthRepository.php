<?php

declare(strict_types=1);

namespace Source\Domain\Repositories;

interface AuthRepository
{
    public function execute(string $email, string $password): array;
    public function userById(int $userId): array;
}