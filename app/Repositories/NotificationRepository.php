<?php


namespace App\Repositories;


use App\Models\Notification;
use App\Models\User;
use App\Repositories\Interfaces\INotificationRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Exception;
use stdClass;

class NotificationRepository implements INotificationRepository
{

    const URL = 'http://o4d9z.mocklab.io/notify';

    public function sendTransaction(Notification $notification): bool
    {
        try {
            $response = Http::get(self::URL);
            return $response->ok() && $response["message"] === "Success";
        } catch (Exception $e)
        {
            return false;
        }
    }

    public function store(Notification $notification): int
    {
        return DB::table('notification')->insertGetId([
            'id_user' => $notification->getUser()->getId(),
            'type' => $notification->getType(),
            'send' => $notification->getSend(),
            'title' => $notification->getTitle(),
            'message' => $notification->getMessage()
        ]);
    }

    public function update(Notification $notification): bool
    {
        return DB::table('notification')
                ->where('id', $notification->getId())
                ->update([
                    'type' => $notification->getType(),
                    'send' => $notification->getSend(),
                    'title' => $notification->getTitle(),
                    'message' => $notification->getMessage()
                ]);
    }

    public function find(int $id): ?stdClass
    {
        return DB::table('notification')->find($id);
    }
}
