<?php


namespace App\Repositories;


use App\Models\Transaction;
use App\Repositories\Interfaces\ITransactionRepository;
use Illuminate\Support\Facades\DB;

class TransactionRepository implements ITransactionRepository
{
    public function store(Transaction $transaction): int
    {
        return DB::table('transaction')->insertGetId([
                'value' => $transaction->getValor(),
                'id_payer' => $transaction->getPayer()->getId(),
                'id_payee' => $transaction->getPayee()->getId()
            ]);
    }

}
