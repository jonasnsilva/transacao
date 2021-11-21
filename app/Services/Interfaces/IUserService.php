<?php

namespace App\Services\Interfaces;

use App\Models\Enum\TipoUsuarioEnum;
use App\Models\User;
use Illuminate\Http\JsonResponse;

interface IUserService
{

    public function store(User $user): bool;

    public function find(string $id): User;

    public function update(User $user): bool;
}
