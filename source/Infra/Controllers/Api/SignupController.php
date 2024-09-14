<?php

declare(strict_types=1);

namespace Source\Infra\Controllers\Api;

use Source\App\Usecases\SignUp\SignupInputBoundary;
use Source\App\Usecases\SignUp\SignupUsecase;
use Source\Domain\ValueObjects\Email;
use Source\Domain\ValueObjects\Password;
use Source\Infra\Repositories\Signup;
use Throwable;

final class SignupController
{
    public function register(array $data): void
    {
        try {
            $email = new Email($data["email"]);
            $password = new Password($data["password"]);

            $input = new SignupInputBoundary(
                $data["first_name"],
                $data["last_name"],
                $data["type"],
                $email,
                $password
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