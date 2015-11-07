<?php

namespace App\Listeners;

use App\Events\FireContentForTemplate;
use App\Repositories\TemplateMarket\TemplateMarketInterface;
use App\Repositories\Template\TemplateInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class InvoiceCheckoutListener
{
    /**
     * TemplateInterface
     * @var $template
     */
    private $template;

    /**
     * TemplateMarket
     * @var $template_mk
     */
    private $template_mk;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(TemplateInterface $template, 
        TemplateMarketInterface $template_mk)
    {
        //
        $this->template = $template;
        $this->template_mk = $template_mk;
    }

    /**
     * Handle the event.
     *
     * @param  FireContentForTemplate  $event
     * @return void
     */
    public function handle(FireContentForTemplate $event)
    {
        $event->saveTemplates($this->template_mk, $this->template);
    }
}
