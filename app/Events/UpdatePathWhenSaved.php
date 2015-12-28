<?php

namespace App\Events;

use App\Events\Event;
use App\Repositories\Category\CategoryInterface;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class UpdatePathWhenSaved extends Event
{
    private $cat_id;
    private $parent_id;

    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($cat_id, $parent_id)
    {
        $this->cat_id = $cat_id;
        $this->parent_id = $parent_id;
    }

    public function updatePath(CategoryInterface $categoryInterface)
    {
        $category = $categoryInterface->getById($this->cat_id);

        if ($categoryInterface->first()) {
            $parent = $categoryInterface->getById($this->parent_id);
            $category->path = $parent->path.$this->cat_id;
        } else {
            $category->path = '0-'.$this->cat_id;
        }
        
        $category->save();
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
