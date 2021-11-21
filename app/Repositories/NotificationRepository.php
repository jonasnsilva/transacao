<?php


namespace App\Repositories;


use App\Models\Notification;
use App\Models\User;
use App\Repositories\Interfaces\INotificationRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Exception;

class NotificationRepository implements INotificationRepository
{

    const URL = 'http://o4d9z.mocklab.io/notify';

    public function sendTransaction(User $payee, float $value): bool
    {
        try {
            $response = Http::get(self::URL);
            return $response->ok() && $response["message"] === "Success";
        } catch (Exception $e)
        {
            return false;
        }
    }

    public function store(Notification $notification)
    {
        DB::table('notification')->insert([
            'id_user' => $notification->getUser()->getId(),
            'type' => $notification->getType(),
            'send' => $notification->getSend()
        ]);
    }
}
