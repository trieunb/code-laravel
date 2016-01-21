<?php

namespace App\Providers;

use App\Providers\UpdatePathWhenSaved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdatePathListener
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
     * @param  UpdatePathWhenSaved  $event
     * @return void
     */
    public function handle(UpdatePathWhenSaved $event)
    {
        //
    }
}
