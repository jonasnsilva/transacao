<?php

namespace App\Services;

use App\Exceptions\UserException;
use App\Models\User;
use App\Repositories\Interfaces\IUserRepository;
use App\Repositories\UserRepository;
use App\Services\Interfaces\IUserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UserService implements IUserService
{
    private IUserRepository $repository;

    public function __construct(
        UserRepository $repository
    )
    {
       $this->repository = $repository;
    }

    public function store(User $user): int
    {
            if ($this->repository->exists($user->getDocument(), $user->getEmail())) {
                throw new UserException('Já existe um usuário com esse documento ou email.');
            } else {
                return $this->repository->store($user);
            }
    }

    public function find(int $id): User
    {
        return $this->repository->find($id);
    }

    public function update(User $user): bool
    {
        return $this->repository->update($user);
    }

    public function addValue(User $user, float $value): bool
    {
        $user->updateBalance($user->getBalance() + $value);
        return $this->repository->update($user);
    }

    public function removeValue(User $user, float $value): bool
    {
        $user->updateBalance($user->getBalance() - $value);
        return $this->repository->update($user);
    }


}
