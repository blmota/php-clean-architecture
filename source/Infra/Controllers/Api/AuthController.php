<?php

declare(strict_types=1);

namespace Source\Infra\Controllers\Api;

use Source\App\Usecases\Auth\AuthInputBoundary;
use Source\App\Usecases\Auth\AuthUsecase;
use Source\Domain\ValueObjects\Email;
use Source\Domain\ValueObjects\Password;
use Source\Infra\Adapters\JwtAdapter;
use Source\Infra\Repositories\Auth;
use Throwable;

final class AuthController extends Api
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(array $data): void
    {
        try {
            $email = new Email($data["email"]);
            $password = new Password($data["password"]);

            $input = new AuthInputBoundary($email, $password);
            $auth = new AuthUsecase(new Auth(), new JwtAdapter);
            $output = $auth->handle($input);

            $json = $output->getDataArray();
            $this->back(["data" => $json]);
        } catch (Throwable $e) {
            $this->call(
                $e->getCode(),
                "error",
                $e->getMessage()
            )->back();
        }
    }
}