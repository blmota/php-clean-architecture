<?php

declare(strict_types=1);

namespace Source\Infra\Repositories;

use DateTime;
use Source\Domain\Entities\Transfer as EntitiesTransfer;
use Source\Domain\Exceptions\TransferFailedException;
use Source\Domain\Repositories\TransferRepository;
use Source\Infra\Database\MariaDB\PdoRepository;

final class Transfer extends PdoRepository implements TransferRepository
{
    public function __construct()
    {
        parent::__construct("tranfer", ["id"], ["user_from", "user_to", "value"]);
    }

    public function execute(EntitiesTransfer $data): EntitiesTransfer
    {
        $doTransfer = $this;
        $doTransfer->user_from = $data->getUserFrom();
        $doTransfer->user_to = $data->getUserTo();
        $doTransfer->value = $data->getValue();

        if (!$doTransfer->save()) {
            throw new TransferFailedException($doTransfer->message()->getText());
        }

        $transfer = new EntitiesTransfer();
        $transfer->setId($doTransfer->id);
        $transfer->setUserFrom($doTransfer->user_from);
        $transfer->setUserTo($doTransfer->user_to);
        $transfer->setValue($doTransfer->value);
        $transfer->setCreatedAt((new DateTime($doTransfer->createdAt)));
        
        if (!empty($doTransfer->updatedAt)) {
            $transfer->setUpdatedAt((new DateTime($doTransfer->updatedAt)));
        }

        return $transfer;
    }
}