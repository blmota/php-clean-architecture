<?php

declare(strict_types=1);

namespace Source\App\Usecases\Wallet\Balance;

use Source\Domain\Repositories\WalletRepository;

final class WalletBalance
{
    private WalletRepository $repository;

    public function __construct(WalletRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(BalanceInputBoundary $input): BalanceOutputBoundary
    {
        $user = $input->getUser();
        $balance = $this->repository->balance($user->getId());

        return new BalanceOutputBoundary([
            "balance" => $balance
        ]);
    }
}
