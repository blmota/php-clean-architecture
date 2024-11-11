<?php

namespace Source\Framework\Support;

use Exception;
use stdClass;

class Http
{
    private string $baseUrl;
    private string $method;
    private ?array $headers;
    private ?array $data;

    private string $response;
    private string $error;

    public function __construct(string $baseUrl, ?array $headers = null, ?array $data = null)
    {
        $this->baseUrl = $baseUrl;
        $this->headers = $headers;
        $this->data = $data;
        $this->error = "";
    }

    public function get(): void
    {
        $this->setMethod("get");
        $this->execute();
    }

    public function post(): void
    {
        $this->setMethod("post");
        $this->execute();
    }

    public function put(): void
    {
        $this->setMethod("put");
        $this->execute();
    }

    public function delete(): void
    {
        $this->setMethod("delete");
        $this->execute();
    }

    public function getResponse(): object
    {
        return json_decode($this->response);
    }

    public function getError(): ?object
    {
        return json_decode($this->error);
    }

    public function print(): string
    {
        return "[{$this->method}] {$this->baseUrl}\n-h {$this->headers}\n-d {$this->data}";
    }

    private function setMethod(string $method): void
    {
        if (!in_array($method, ["get", "post", "put", "delete"])) {
            throw new Exception("O método informado para a requisição não é válido.");
        }

        $this->method = $method;
    }

    private function execute(): void
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->baseUrl);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 14);

        switch ($this->method) {
            case "get":
                // Equivalente ao -X:
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                break;
            case "post":
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $this->data);  //Post Fields
                break;
            case "put":
                // Equivalente ao -X:
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                if (!empty($data)) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $this->data);  //Post Fields
                }
                break;
            case "delete":
                // Equivalente ao -X:
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ($this->headers) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        }

        $server_output = curl_exec($ch);

        if ($server_output == false) {
            $errorCode = curl_errno($ch);
            $err = curl_error($ch);

            $errorObject = new stdClass();
            $errorObject->code = $errorCode;
            $errorObject->message = $err;
            $this->error = json_encode($errorObject);
            throw new Exception("{$errorObject->message} - Code: {$errorObject->code}");
        }

        curl_close($ch);
        $this->response = $server_output;
    }
}
