<?php

declare(strict_types=1);

namespace Source\App\Usecases\Auth;

use DateTime;
use Source\App\Contracts\AuthToken;
use Source\Domain\Repositories\AuthRepository;

final class AuthUsecase
{
    private AuthRepository $repository;
    private AuthToken $authToken;

    public function __construct(
        AuthRepository $repository,
        AuthToken $authToken
    ) {
        $this->repository = $repository;
        $this->authToken = $authToken;
    }

    public function handle(AuthInputBoundary $input): AuthOutputBoundary
    {
        $response = $this->repository->execute($input->getEmail(), $input->getPassword());
        $response["created_at"] = (new DateTime($response["created_at"]))->format("d/m/Y H\hi");
        $response["authenticate"] = [
            "token" => $this->authToken->tokenGenerate($response["id"]),
            "refreshToken" => $this->authToken->refreshTokenGenerate($response["id"])
        ];
        return new AuthOutputBoundary($response);
    }
}
