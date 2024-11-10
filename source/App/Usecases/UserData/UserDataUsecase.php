<?php

declare(strict_types=1);

namespace Source\App\Usecases\UserData;

use Source\Domain\Repositories\AuthRepository;

class UserDataUsecase
{
    private AuthRepository $repository;

    public function __construct(AuthRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(UserDataInputBoundary $input): array
    {
        $output = $this->repository->userById($input->getUserId());
        unset($output["password"]);
        return $output;
        //return new UserDataOutputBoundary($output);
    }
}
