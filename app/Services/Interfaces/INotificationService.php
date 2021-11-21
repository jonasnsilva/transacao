<?php


namespace App\Services\Interfaces;


use App\Models\User;

interface INotificationService
{

    public function sendTransaction(User $payee, float $value, string $type): void;
}
