<?php


namespace App\Services\Interfaces;


use App\Models\User;

interface ITransactionService
{

    public function store(User $payer, User $payee, float $value): int;

    public function validateTransaction(User $payer, float $value): bool;

}
