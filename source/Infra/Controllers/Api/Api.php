<?php

namespace Source\Infra\Controllers\Api;

use Source\Domain\Entities\User;

class Api
{
    protected ?User $user;
    protected array $headers;
    protected ?array $response;

    public function __construct()
    {
        header('Content-Type: application/json; charset=UTF-8');
        $this->headers = array_change_key_case(getallheaders(), CASE_LOWER);
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