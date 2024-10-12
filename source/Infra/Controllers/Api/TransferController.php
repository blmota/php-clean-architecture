<?php

declare(strict_types=1);

namespace Source\Infra\Controllers\Api;

use Source\App\Usecases\Transfer\TransferInputBoundary;
use Source\App\Usecases\Transfer\TransferUsecase;
use Source\App\Usecases\Wallet\AddValue\WalletAddValueInputBoundary;
use Source\App\Usecases\Wallet\AddValue\WalletAddValueUsecase;
use Source\App\Usecases\Wallet\SubValue\WalletSubValueInputBoundary;
use Source\App\Usecases\Wallet\SubValue\WalletSubValueUsecase;
use Source\Framework\Core\Transaction;
use Source\Infra\Repositories\Transfer;
use Source\Infra\Repositories\Wallet;
use Throwable;

class TransferController extends Api
{
    public function __construct()
    {
        parent::__construct();
    }

    public function doTransfer(array $data): void
    {
        try {
            Transaction::open();
            
            $inputTransfer = new TransferInputBoundary($data["user_from"], $data["user_to"], $data["value"]);
            $doTransfer = (new TransferUsecase(new Transfer()))->handle($inputTransfer);

            // subtrai valor da conta de origem
            $inputSubValue = new WalletSubValueInputBoundary($data["user_from"], $data["value"]);
            (new WalletSubValueUsecase(new Wallet()))->handle($inputSubValue);

            // adiciona valor na conta de destino
            $inputAddValue = new WalletAddValueInputBoundary($data["userTo"], $data["value"]);
            (new WalletAddValueUsecase(new Wallet()))->handle($inputAddValue);

            Transaction::close();
            $this->back(["success" => true, "data" => (array) $doTransfer]);
        } catch (Throwable $e) {
            Transaction::rollback();

            $this->call(
                $e->getCode(),
                "error",
                $e->getMessage()
            )->back();
        }
    }
}
