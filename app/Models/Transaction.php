<?php

namespace App\Models;


class Transaction
{
    private float $value;
    private User $payee;
    private User $payer;


    public function __construct(array $transaction)
    {
        $this->value = $transaction["value"];
        $this->payer = $transaction["payer"];
        $this->payee = $transaction["payee"];
    }

    /**
     * @return float
     */
    public function getValor(): float
    {
        return $this->value;
    }

    /**
     * @return User
     */
    public function getPayee(): User
    {
        return $this->payee;
    }

    /**
     * @return User
     */
    public function getPayer(): User
    {
        return $this->payer;
    }
}
