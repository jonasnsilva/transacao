<?php


namespace App\Repositories\Interfaces;


use App\Models\Notification;
use App\Models\User;

interface INotificationRepository
{

    public function sendTransaction(User $payee, float $value): bool;

    public function store(Notification $notification);



}
