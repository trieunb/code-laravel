<?php
namespace App\Listeners;

use App\Events\GetCountryAndRegionFromLocationUser;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class FireEventCreateCountryRegion 
{
	/**
     * Create the event listener.
     *
     * @return void
     */
	public function __construct()
	{
		
	}

	/**
     * Handle the event.
     *
     * @param  GetCountryAndRegionFromLocationUser  $event
     * @return void
     */
	public function handle(GetCountryAndRegionFromLocationUser $event)
	{
		$event->event();
	}
}