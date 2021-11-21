<?php
namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\IUserRepository;
use Illuminate\Support\Facades\DB;
use stdClass;

class UserRepository implements IUserRepository
{


    public function store(User $user): int
    {
            return DB::table('user')->insertGetId([
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'password' => $user->getPassword(),
                'document' => $user->getDocument(),
                'user_type' => $user->getUserType(),
                'balance' => $user->getBalance()
            ]);
    }

    public function exists(string $document, string $email): bool
    {

            return DB::table('user')
                ->select('*')
                ->where('document', '=', $document)
                ->orWhere('email', '=', $email)
                ->limit(1)
                ->count() > 0;


    }

    public function find(string $id): ?stdClass
    {
            return DB::table('user')->find($id);

    }

    public function update(User $user): bool
    {
            return DB::table('user')
                ->where('id', $user->getId())
                ->update([
                    'name' => $user->getName(),
                    'email' => $user->getPassword(),
                    'user_type' => $user->getUserType(),
                    'balance' => $user->getBalance()
                ]);

    }
}
