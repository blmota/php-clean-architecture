<?php

declare(strict_types=1);

namespace Source\App\Usecases\SignUp;

final class SignupOutputBoundary
{
    private int $id;
    private string $firstName;
    private string $lastName;
    private string $type;
    private string $email;
    private int $level;
    private string $createdAt;

    public function __construct(array $data)
    {
        $this->id = $data["id"];
        $this->firstName = $data["first_name"];
        $this->lastName = $data["last_name"];
        $this->type = $data["type"];
        $this->email = $data["email"];
        $this->level = $data["level"];
        $this->createdAt = $data["created_at"];
    }

    public function getDataArray(): array
    {
        return [
            "id" => $this->id,
            "first_name" => $this->firstName,
            "last_name" => $this->lastName,
            "type" => $this->type,
            "email" => $this->email,
            "level" => $this->level,
            "created_at" => $this->createdAt
        ];
    }
}
