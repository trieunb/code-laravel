<?php

namespace App\Listeners;

use App\Events\RenderFileWhenCreateTemplateMarket;
use App\Repositories\TemplateMarket\TemplateMarketInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RenderFileTemplateMarketListener
{
    /**
     * TemplateMarketInterface
     * @var $template
     */
    private $template;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(TemplateMarketInterface $template)
    {
        $this->template = $template;
    }

    /**
     * Handle the event.
     *
     * @param  RenderFileWhenCreateTemplateMarket  $event
     * @return void
     */
    public function handle(RenderFileWhenCreateTemplateMarket $event)
    {
        $event->render($this->template);
    }
}
