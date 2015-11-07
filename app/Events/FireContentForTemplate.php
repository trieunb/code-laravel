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

    private $template_mk_ids;
    private $user_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array $template_mk_ids, $user_id)
    {
        $this->template_mk_ids = $template_mk_ids;
        $this->user_id = $user_id;
    }

    public function saveTemplates(TemplateMarketInterface $template_mk ,
        TemplateInterface $template)
    {
        $template_mk = $template_mk->getDataWhereIn('id', $this->template_mk_ids);
        $data = [];

        foreach ($template_mk as $value) {
            $data[] = [
                'user_id' => $this->user_id,
                'content' => $value->content,
                'image'   => $value->image,
                'title'   => $value->title
            ];
        }

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
