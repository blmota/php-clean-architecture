<?php

declare(strict_types=1);

namespace Source\Infra\Repositories;

use Source\Domain\Entities\Transfer as EntitiesTransfer;
use Source\Domain\Exceptions\TransferFailedException;
use Source\Domain\Repositories\TransferRepository;
use Source\Infra\Database\MariaDB\PdoRepository;

final class Transfer extends PdoRepository implements TransferRepository
{
    public function __construct()
    {
        parent::__construct("transfer", ["id"], ["user_from", "user_to", "value"]);
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
        $transfer->hydrate((array) $doTransfer->data());
        return $transfer;
    }
}
