<?php


namespace App\Services;


use App\Jobs\SendNotificationTransaction;
use App\Models\Enum\SendNotificationEnum;
use App\Models\Notification;
use App\Models\User;
use App\Repositories\Interfaces\INotificationRepository;
use App\Repositories\NotificationRepository;
use App\Services\Interfaces\INotificationService;

class NotificationService implements INotificationService
{

    const URL = 'http://o4d9z.mocklab.io/notify';

    private INotificationRepository $repository;

    public function __construct(
        NotificationRepository $repository
    )
    {
        $this->repository = $repository;
    }
    public function sendTransaction(User $payee, float $value, string $type): void
    {

       $notifield = $this->repository->sendTransaction($payee, $value);
        if(!$notifield)
        {
            dispatch(new SendNotificationTransaction());
        }
       $this->repository->store(new Notification([
           "type" => $type,
           "send" => $notifield ? SendNotificationEnum::YES : SendNotificationEnum::NO,
           "user" => $payee]));


    }
}
