<?php


namespace App\Repositories;


use App\Models\Transaction;
use App\Repositories\Interfaces\ITransactionRepository;
use Illuminate\Support\Facades\DB;

class TransactionRepository implements ITransactionRepository
{
    public function store(Transaction $transaction)
    {
        try {
        return DB::table('transaction')->insert([
                'value' => $transaction->getValor(),
                'id_payer' => $transaction->getPayer()->getId(),
                'id_payee' => $transaction->getPayee()->getId()
            ]);
        } catch (\Exception $exception)
        {
            return false;
        }
    }

}
