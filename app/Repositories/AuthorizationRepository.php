<?php


namespace App\Repositories;


use App\Repositories\Interfaces\IAuthorizationRepository;
use Illuminate\Support\Facades\Http;
use Exception;

class AuthorizationRepository implements IAuthorizationRepository
{
    const URL = 'https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6';

    public function transactionAuthorization(): bool
    {
        try {
            $response = Http::get(self::URL);
            return $response->ok() && $response["message"] === "Autorizado";
        } catch (Exception $e)
        {
            return false;
        }
    }
}
