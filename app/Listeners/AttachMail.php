<?php

namespace App\Listeners;

use App\Events\sendMailAttachFile;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AttachMail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
     
    }

    /**
     * Handle the event.
     *
     * @param  sendMailAttachFile  $event
     * @return void
     */
    public function handle(sendMailAttachFile $event)
    {
        $event->send();
    }
}
