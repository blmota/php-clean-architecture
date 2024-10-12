<?php

namespace Source\Infra\Controllers\Api;

use Source\App\Usecases\UserData\UserDataInputBoundary;
use Source\App\Usecases\UserData\UserDataUsecase;
use Source\Domain\Entities\User;
use Source\Infra\Adapters\JwtAdapter;
use Source\Infra\Repositories\Auth;

class Api
{
    protected ?User $user;
    protected array $headers;
    protected ?array $response;

    public function __construct()
    {
        header('Content-Type: application/json; charset=UTF-8');
        $this->headers = array_change_key_case(getallheaders(), CASE_LOWER);

        if (!$this->auth()) {
            exit;
        }
    }

    protected function auth(): bool
    {
        if (empty($this->headers["token"])) {
            $this->call(
                401,
                "unauthorized",
                "Informe o token de acesso."
            )->back();
            return false;
        }

        $token = (new JwtAdapter)->tokenValidate($this->headers["token"]);

        if (!$token["status"]) {
            $this->call(
                401,
                "unauthorized",
                "Access token inválido."
            )->back();
            return false;
        }

        $userId = $token["sub"];
        $input = new UserDataInputBoundary($userId);
        $userData = (new UserDataUsecase(new Auth))->handle($input);
        
        $user = new User;
        $user->setId($userData["id"]);
        $user->setFirstName($userData["first_name"]);
        $user->setLastName($userData["last_name"]);
        $user->setType($userData["type"]);
        $user->setEmail($userData["email"]);
        $user->setLevel($userData["level"]);
        $user->setStatus($userData["status"]);

        $this->user = $user;
        return true;
    }

    protected function call(int $code, string $type = null, string $message = null, string $rule = "errors"): Api
    {
        http_response_code($code);

        if (!empty($type)) {
            $this->response = [
                $rule => [
                    "type" => $type,
                    "message" => (!empty($message) ? $message : null)
                ]
            ];
        }
        return $this;
    }

    protected function back(array $response = null): Api
    {
        if (!empty($response)) {
            $this->response = (!empty($this->response) ? array_merge($this->response, $response) : $response);
        }

        echo json_encode($this->response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        return $this;
    }
}