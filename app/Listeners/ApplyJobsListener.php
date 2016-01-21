<?php

namespace App\Listeners;

use App\Events\applyJobsEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApplyJobsListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  applyJobs  $event
     * @return void
     */
    public function handle(applyJobsEvent $event)
    {
        $event->send();
    }
}
