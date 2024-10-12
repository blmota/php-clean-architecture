<?php

declare(strict_types=1);

namespace Source\App\Usecases\Wallet\SubValue;

use Source\Domain\Repositories\WalletRepository;

final class WalletSubValueUsecase
{
    private WalletRepository $repository;

    public function __construct(WalletRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(WalletSubValueInputBoundary $input): WalletSubValueOutputBoundary
    {
        $response = $this->repository->subValue($input->getUserId(), $input->getValue());
        return new WalletSubValueOutputBoundary($response);
    }
}
