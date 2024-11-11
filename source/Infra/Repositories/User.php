<?php

declare(strict_types=1);

namespace Source\Infra\Repositories;

use Source\Infra\Database\MariaDB\PdoRepository;

final class User extends PdoRepository
{
    public function __construct()
    {
        parent::__construct("users", ["id"], ["first_name", "last_name", "type"]);
    }
}
