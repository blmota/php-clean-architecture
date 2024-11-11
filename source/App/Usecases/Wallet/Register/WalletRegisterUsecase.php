<?php

declare(strict_types=1);

namespace Source\App\Usecases\Wallet\Register;

use Source\Domain\Entities\Wallet;
use Source\Domain\Repositories\WalletRepository;

final class WalletRegisterUsecase
{
    private WalletRepository $repository;

    public function __construct(WalletRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(WalletRegisterInputBoundary $input): WalletRegisterOutputBoundary
    {
        $newWallet = new Wallet();
        $newWallet->setUserId($input->getUserId());
        $newWallet->setValue(0);
        $response = $this->repository->register($newWallet);
        return new WalletRegisterOutputBoundary($response);
    }
}
