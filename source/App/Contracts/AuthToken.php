<?php

declare(strict_types=1);

namespace Source\App\Contracts;

interface AuthToken
{
    public function tokenGenerate(int $userId): ?array;
    public function tokenValidate(string $token): array;
    public function refreshTokenGenerate(int $userId): ?array;
    public function refreshTokenValidate(string $token): bool;
}