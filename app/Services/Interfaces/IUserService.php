<?php

namespace App\Services\Interfaces;

use App\Models\Enum\TipoUsuarioEnum;
use App\Models\User;
use Illuminate\Http\JsonResponse;

interface IUserService
{

    public function store(User $user): int;

    public function find(int $id): User;

    public function update(User $user): bool;
}
