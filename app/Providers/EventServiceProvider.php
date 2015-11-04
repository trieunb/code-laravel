<?php

namespace App\Providers;

use App\Events\ConvertHtmlToDocxAfterEditTemplate;
use App\Events\sendMailAttachFile;
use App\Handlers\Events\ConvertListener;
use App\Listeners\AttachMail;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        UpdatePathWhenSaved::class => [
           UpdatePathListener::class,
        ],
        ConvertHtmlToDocxAfterEditTemplate::class => [
            ConvertListener::class
        ],
        sendMailAttachFile::class => [
            AttachMail::class
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
