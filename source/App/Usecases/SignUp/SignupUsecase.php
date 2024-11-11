<?php

declare(strict_types=1);

namespace Source\App\Usecases\SignUp;

use DateTime;
use Source\Domain\Entities\User;
use Source\Domain\Repositories\SignupRepository;

final class SignupUsecase
{
    private SignupRepository $repository;

    public function __construct(SignupRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(SignupInputBoundary $input): SignupOutputBoundary
    {
        $newUser = new User();
        $newUser->setFirstName($input->getFirstName());
        $newUser->setLastName($input->getLastName());
        $newUser->setType($input->getType());
        $newUser->setEmail($input->getEmail());
        $newUser->setPassword($input->getPassword());
        $newUser->setLevel(1);

        $response = $this->repository->register($newUser);
        $response["created_at"] = (new DateTime($response["created_at"]))->format("d/m/Y H\hi");
        return new SignupOutputBoundary($response);
    }
}
