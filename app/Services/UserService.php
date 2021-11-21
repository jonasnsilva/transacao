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

    public function store(User $user): JsonResponse
    {
            if ($this->repository->exists($user->getDocument(), $user->getEmail())) {
                throw new UserException('Já existe um usuário com esse documento ou email.');
            } else {
                if($this->repository->store($user))
                {
                    return response()->json([
                        'code' => Response::HTTP_CREATED,
                        'message' => 'Usuário cadastrado com sucesso'
                    ], Response::HTTP_CREATED);
                }
            }
    }

    public function find(string $id): User
    {
        $user = $this->repository->find($id);
        if($user)
        {
            return new User((array)$user);
        } else {
            throw new UserException('Usuário não encontrado.');
        }
    }

    public function update(User $user): bool
    {
        return $this->repository->update($user);
    }

    public function addValue(User $user, float $value)
    {
        $user->updateBalance($user->getBalance() + $value);
        return $this->repository->update($user);
    }

    public function removeValue(User $user, float $value)
    {
        $user->updateBalance($user->getBalance() - $value);
        return $this->repository->update($user);
    }


}
