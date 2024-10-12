<?php

declare(strict_types=1);

namespace Source\Infra\Repositories;

use Exception;
use Source\Domain\Repositories\AuthRepository;
use Source\Infra\Database\MariaDB\PdoRepository;

final class Auth extends PdoRepository implements AuthRepository
{
    public function __construct()
    {
        parent::__construct("users", ["id"], []);
    }

    public function execute(string $email, string $password): array
    {
        $user = $this->find("email = ':email'", "email={$email}")->fetch();

        if (empty($user)) {
            throw new Exception("O e-mail informado não está cadatrado.");
        }

        if (passwd_verify($password, $user->password)) {
            throw new Exception("A senha informada está incorreta.");
        }

        return (array) $user->data();
    }

    public function userById(int $userId): array
    {
        $user = $this->findById($userId);

        if (empty($user)) {
            throw new Exception("O usuário não existe.");
        }

        return (array) $user->data();
    }
}