<?php

declare(strict_types=1);

namespace Source\App\Usecases\Wallet\AddValue;

use Source\Domain\Repositories\WalletRepository;

final class WalletAddValueUsecase
{
    private WalletRepository $repository;

    public function __construct(WalletRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(WalletAddValueInputBoundary $input): WalletAddValueOutputBoundary
    {
        $response = $this->repository->addValue($input->getUserId(), $input->getValue());
        return new WalletAddValueOutputBoundary($response);
    }
}
