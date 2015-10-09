<?php

namespace App\Listeners;

use App\Events\UpdatePathWhenSaved;
use App\Repositories\Category\CategoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdatePathListener
{
    protected $category;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(CategoryInterface $category)
    {
        $this->category = $category;
    }

    /**
     * Handle the event.
     *
     * @param  UpdatePathWhenSaved  $event
     * @return void
     */
    public function handle(UpdatePathWhenSaved $event)
    {
        $event->updatePath($this->category);
    }
}
