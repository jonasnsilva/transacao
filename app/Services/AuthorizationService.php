<?php


namespace App\Services;


use App\Repositories\Interfaces\IAuthorizationRepository;
use App\Services\Interfaces\IAuthorizationService;

class AuthorizationService implements IAuthorizationService
{
    private IAuthorizationRepository $repository;

    public function __construct(
        IAuthorizationRepository $repository
    )
    {
        $this->repository = $repository;
    }

    public function transactionAuthorization(): bool
    {
        return $this->repository->transactionAuthorization();
    }

}
