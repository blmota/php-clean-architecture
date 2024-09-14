<?php

declare(strict_types=1);

namespace Source\Infra\Controllers\Api;

use Source\App\Usecases\SignUp\SignupInputBoundary;
use Source\App\Usecases\SignUp\SignupUsecase;
use Source\Infra\Repositories\Signup;
use Throwable;

final class SignupController
{
    public function __construct()
    {
        header('Content-Type: application/json; charset=UTF-8');
    }

    public function register(array $data): void
    {
        try {
            $input = new SignupInputBoundary(
                $data["first_name"],
                $data["last_name"],
                $data["type"],
                $data["email"],
                $data["password"]
            );

            $register = new SignupUsecase(new Signup);
            $output = $register->handle($input);
            $json = $output->getDataArray();
            echo json_encode(["data" => $json], JSON_PRETTY_PRINT);
        } catch (Throwable $e) {
            $error = [
                "type" => "error",
                "message" => $e->getMessage()
            ];
            echo json_encode(["errors" => $error], JSON_PRETTY_PRINT);
        }
    }
}