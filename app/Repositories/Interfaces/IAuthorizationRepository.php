<?php


namespace App\Repositories\Interfaces;


interface IAuthorizationRepository
{
    public function transactionAuthorization(): bool;

}
