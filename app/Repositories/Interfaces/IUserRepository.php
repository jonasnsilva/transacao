<?php
namespace App\Repositories\Interfaces;

use App\Models\User;

interface IUserRepository
{
    public function store(User $user): bool;

    public function find(string $id);

    public function update(User $user): bool;

}
