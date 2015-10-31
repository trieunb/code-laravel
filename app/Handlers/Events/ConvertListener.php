<?php

namespace App\Handlers\Events;

use App\Events\ConvertHtmlToDocxAfterEditTemplate;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ConvertListener
{
    /**
     * Create the event handler.
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
     * @param  ConvertHtmlToDocxAfterEditTemplate  $event
     * @return void
     */
    public function handle(ConvertHtmlToDocxAfterEditTemplate $event)
    {
        //
    }
}
