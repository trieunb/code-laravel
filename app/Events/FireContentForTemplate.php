<?php

namespace App\Events;

use App\Events\Event;
use App\Repositories\TemplateMarket\TemplateMarketInterface;
use App\Repositories\Template\TemplateInterface;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class FireContentForTemplate extends Event
{
    use SerializesModels;

    private $template_mk_id;
    private $user_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($template_mk_id, $user_id)
    {
        $this->template_mk_id = $template_mk_id;
        $this->user_id = $user_id;
    }

    public function saveTemplates(TemplateMarketInterface $template_mk ,
        TemplateInterface $template)
    {
        $template_mk = $template_mk->getById($this->template_mk_id);
        $clone = [
            'clone_by' => $template_mk->clone_id != null ? 'templates' : 'template_markets',
            'clone_id' => $template_mk->clone_id != null ?: $this->template_mk_id
        ];

        $data = [
            'user_id' => $this->user_id,
            'content' => $template_mk->content,
            'image'   => $template_mk->image,
            'title'   => $template_mk->title,
            'type'    => 0,
            'source_file_pdf' => $template_mk->source_file_pdf,
            'clone' => $clone,
            'version' => $template_mk->version
        ];

        $template->createTemplateFromMarket($data);
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
