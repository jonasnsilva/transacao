<?php

namespace App\Jobs;

class SendNotificationTransaction extends Job
{

    public $tries = 10;
    public $backoff = [120, 240];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
