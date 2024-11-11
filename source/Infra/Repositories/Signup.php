<?php

declare(strict_types=1);

namespace Source\Infra\Repositories;

use Source\Domain\Entities\User;
use Source\Domain\Repositories\SignupRepository;
use Source\Infra\Database\MariaDB\PdoRepository;
use Source\Domain\Exceptions\RepositoryFailedException;

final class Signup extends PdoRepository implements SignupRepository
{
    public function __construct()
    {
        parent::__construct("users", ["id"], ["first_name", "last_name", "type", "email"]);
    }

    public function register(User $newUser): array
    {
        $this->first_name = $newUser->getFirstName();
        $this->last_name = $newUser->getLastName();
        $this->type = $newUser->getType();
        $this->email = (string) $newUser->getEmail();
        $this->password = (string) $newUser->getPassword();
        $this->level = $newUser->getLevel();
        $this->status = 1;

        if (!$this->save()) {
            throw new RepositoryFailedException($this->message()->getText());
        }

        return (array) $this->data();
    }
}
