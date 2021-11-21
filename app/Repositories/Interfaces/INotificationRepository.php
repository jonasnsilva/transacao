<?php


namespace App\Repositories\Interfaces;


use App\Models\Notification;
use stdClass;

interface INotificationRepository
{

    public function sendTransaction(Notification $notification): bool;

    public function store(Notification $notification): int;

    public function find(int $id): ?stdClass;

    public function update(Notification $notification): bool;

}
