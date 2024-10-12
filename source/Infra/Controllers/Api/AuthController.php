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

final class AuthController
{
    public function __construct()
    {
        header('Content-Type: application/json; charset=UTF-8');
    }

    public function index(array $data): void
    {
        try {
            $email = new Email($data["email"]);
            $password = new Password($data["password"]);

            $input = new AuthInputBoundary($email, $password);
            $auth = new AuthUsecase(new Auth(), new JwtAdapter());
            $output = $auth->handle($input);

            echo json_encode(["data" => $output->getDataArray()], JSON_PRETTY_PRINT);
        } catch (Throwable $e) {
            http_response_code($e->getCode());

            echo json_encode([
                "errors" => [
                    "type" => "error",
                    "message" => $e->getMessage()
                ]
            ], JSON_PRETTY_PRINT);
        }
    }
}
