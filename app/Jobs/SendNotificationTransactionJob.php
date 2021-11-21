<?php

namespace App\Jobs;

use App\Models\Notification;

class SendNotificationTransactionJob extends Job
{

    public $tries = 15;
    public $backoff = [120, 240];

    private Notification $notification;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Notification $notification)
    {
        $this->$notification = $notification;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //em construção.
        $this->release(120);
    }

}
