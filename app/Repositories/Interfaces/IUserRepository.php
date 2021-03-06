<?php
namespace App\Repositories\Interfaces;

use App\Models\User;
use stdClass;

interface IUserRepository
{
    public function store(User $user): int;

    public function find(string $id): User;

    public function update(User $user): bool;

}
