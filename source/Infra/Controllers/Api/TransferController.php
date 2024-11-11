<?php

declare(strict_types=1);

namespace Source\Infra\Controllers\Api;

use DateTime;
use Exception;
use Source\App\Usecases\Transfer\TransferInputBoundary;
use Source\App\Usecases\Transfer\TransferUsecase;
use Source\App\Usecases\UserData\UserDataInputBoundary;
use Source\App\Usecases\UserData\UserDataUsecase;
use Source\App\Usecases\Wallet\AddValue\WalletAddValueInputBoundary;
use Source\App\Usecases\Wallet\AddValue\WalletAddValueUsecase;
use Source\App\Usecases\Wallet\SubValue\WalletSubValueInputBoundary;
use Source\App\Usecases\Wallet\SubValue\WalletSubValueUsecase;
use Source\Domain\Entities\User;
use Source\Framework\Core\Transaction;
use Source\Framework\Support\Http;
use Source\Infra\Repositories\Auth;
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

            $userFrom = $this->user;

            $input = new UserDataInputBoundary((int) $data["user_to"]);
            $userToData = (new UserDataUsecase(new Auth()))->handle($input);
            $userToData["created_at"] = new DateTime($userToData["created_at"]);
            $userToData["updated_at"] = (!empty($userToData["updated_at"])
                ? new DateTime($userToData["updated_at"])
                : null
            );

            $userTo = new User();
            $userTo->hydrate($userToData);
            $value = floatval($data["value"]);

            $inputTransfer = new TransferInputBoundary($userFrom, $userTo, $value);
            $doTransfer = (new TransferUsecase(new Transfer()))->handle($inputTransfer);

            // subtrai valor da conta de origem
            $inputSubValue = new WalletSubValueInputBoundary($userFrom->getId(), $value);
            (new WalletSubValueUsecase(new Wallet()))->handle($inputSubValue);

            // adiciona valor na conta de destino
            $inputAddValue = new WalletAddValueInputBoundary($userTo->getId(), $value);
            (new WalletAddValueUsecase(new Wallet()))->handle($inputAddValue);

            $validateOperation = new Http("https://util.devi.tools/api/v2/authorize");
            $validateOperation->get();

            if ($validateOperation->getResponse()->status != "success") {
                throw new Exception("A tranferência não pode ser concluída pela operadora, 
                    tente novamente mais tarde.");
            }

            Transaction::close();
            $this->back(["success" => true, "data" => $doTransfer->getDataArray()]);
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
