<?php

namespace App\Jobs;

use App\Exceptions\NotificationException;
use App\Models\Enum\SendNotificationEnum;
use App\Repositories\NotificationRepository;
use App\Repositories\UserRepository;
use App\Services\Interfaces\INotificationService;
use App\Services\NotificationService;
use App\Services\UserService;
use Exception;
use Illuminate\Http\Response;

class SendNotificationTransactionJob extends Job
{

    public $tries = 15;
    public $backoff = [120, 240];

    private int $idNotification;

    /**
     * Create a new job instance.
     *
     * @param int $idNotification
     */
    public function __construct(int $idNotification)
    {
        $this->idNotification = $idNotification;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        try {
            $serviceNotification = new NotificationService(
                new NotificationRepository(),
                new UserService(new UserRepository()
                )
            );
            $notification = $serviceNotification->find($this->idNotification);
            if($serviceNotification->sendFailedTransaction($notification))
            {
                $notification->setSend(SendNotificationEnum::YES);
                $serviceNotification->update($notification);
            } else {
                $this->release(5);
            }


        } catch (NotificationException $ne)
        {
            if($ne->getCode() === Response::HTTP_UNPROCESSABLE_ENTITY)
            {
                $this->fail($ne);
            } else {
                $this->release(5);
            }
        } catch (Exception $e)
        {
            $this->fail($e);
            $this->release(5);
        }
    }

}
