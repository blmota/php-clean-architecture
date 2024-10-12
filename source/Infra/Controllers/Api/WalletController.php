<?php

declare(strict_types=1);

namespace Source\Infra\Controllers\Api;

use Exception;
use Source\App\Usecases\Wallet\AddValue\WalletAddValueInputBoundary;
use Source\App\Usecases\Wallet\AddValue\WalletAddValueUsecase;
use Source\App\Usecases\Wallet\Balance\BalanceInputBoundary;
use Source\App\Usecases\Wallet\Balance\WalletBalance;
use Source\App\Usecases\Wallet\Register\WalletRegisterInputBoundary;
use Source\App\Usecases\Wallet\Register\WalletRegisterUsecase;
use Source\App\Usecases\Wallet\SubValue\WalletSubValueInputBoundary;
use Source\App\Usecases\Wallet\SubValue\WalletSubValueUsecase;
use Source\Framework\Core\Transaction;
use Source\Infra\Repositories\Wallet;
use Throwable;

class WalletController extends Api
{
    public function __construct()
    {
        parent::__construct();
    }

    public function balance(): void
    {
        try {
            $input = new BalanceInputBoundary($this->user);
            $output = (new WalletBalance(new Wallet()))->handle($input);
            $this->back(["data" => ["balance" => $output->getBalance()]]);
        } catch (Throwable $e) {
            $this->call(
                $e->getCode(),
                "error",
                $e->getMessage()
            )->back();
        }
    }

    public function register(array $data): void
    {
        try {
            Transaction::open();
            $data["user_id"] = $this->user->getId();
            $data["value"] = (int) 0;
            $input = new WalletRegisterInputBoundary($data);
            $output = (new WalletRegisterUsecase(new Wallet()))->handle($input);
            Transaction::close();
            $this->back(["data" => $output->getDataArray()]);
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
