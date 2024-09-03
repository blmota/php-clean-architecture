<?php

declare(strict_types=1);

namespace Source\App\Usecases\Wallet\Balance;

use Source\Domain\Entities\User;

final class BalanceInputBoundary
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}