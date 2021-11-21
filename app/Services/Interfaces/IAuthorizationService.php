<?php


namespace App\Services\Interfaces;


interface IAuthorizationService
{

    public function transactionAuthorization(): bool;

}
