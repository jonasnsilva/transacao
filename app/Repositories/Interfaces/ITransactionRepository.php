<?php


namespace App\Repositories\Interfaces;


use App\Models\Transaction;

interface ITransactionRepository
{
    public function store(Transaction $transaction): int;
}
