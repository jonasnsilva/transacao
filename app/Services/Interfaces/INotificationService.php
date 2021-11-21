<?php


namespace App\Services\Interfaces;


use App\Models\Notification;
use App\Models\User;

interface INotificationService
{

    public function sendTransaction(User $payer, User $payee, float $value): void;

    public function sendFailedTransaction(Notification $notification): bool;

    public function find(int $id): Notification;

    public function update(Notification $notification): bool;
}
