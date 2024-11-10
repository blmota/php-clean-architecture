<?php

declare(strict_types=1);

namespace Source\App\Usecases\Transfer;

use Exception;
use Source\Domain\Entities\Transfer as EntitiesTransfer;
use Source\Domain\Repositories\TransferRepository;

final class TransferUsecase
{
    private TransferRepository $respository;

    public function __construct(TransferRepository $repository)
    {
        $this->respository = $repository;
    }

    public function handle(TransferInputBoundary $input): TransferOutputBoundary
    {   
        $userFrom = $input->getUserFrom();

        if ($userFrom->getType() == "J") {
            throw new Exception("Somente pessoas físicas podem realizar transferências para outras contas.");
        }

        $userTo = $input->getUserTo();

        if ($userFrom->getId() == $userTo->getId()) {
            throw new Exception("Você não pode realizar transferências para sua própria carteira.");
        }

        $transfer = new EntitiesTransfer();
        $transfer->setUserFrom($userFrom->getId());
        $transfer->setUserTo($userTo->getId());
        $transfer->setValue($input->getValue());

        $doTransfer = $this->respository->execute($transfer);

        return new TransferOutputBoundary([
            "id" => $doTransfer->getId(),
            "userNameFrom" => $userFrom->getFullName(),
            "userNameTo" => $userTo->getFullName(),
            "value" => "R$ " . str_price((string) $doTransfer->getValue()),
            "createdAt" => $doTransfer->getCreatedAt()->format("d/m/Y H\hi")
        ]);
    }
}