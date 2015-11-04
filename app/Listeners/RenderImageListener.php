<?php

namespace App\Listeners;

use App\Events\RenderImageAfterCreateTemplate;
use App\Repositories\Template\TemplateInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RenderImageListener
{
    private $template;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(TemplateInterface $template)
    {
        
        $this->template = $template;
    }

    /**
     * Handle the event.
     *
     * @param  RenderImageAfterCreateTemplate  $event
     * @return void
     */
    public function handle(RenderImageAfterCreateTemplate $event)
    {
        return $event->render($this->template);
    }
}
