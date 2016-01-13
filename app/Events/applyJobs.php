<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class applyJobs extends Event
{

    private $company;
    private $pathFilePDF;
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($company, $pathFilePDF)
    {
        $this->company = $company;
        $this->pathFilePDF = $pathFilePDF;
    }

    public function send()
    {
        $company = $this->company;
        $pathFilePDF = $this->pathFilePDF;
       \Log::info('send mail', [$pathFilePDF]);
        \Mail::queue('emails.send_attach_file', compact('company'), function($message) use($company, $pathFilePDF){
            $message->from(env('MAIL_USERNAME'));
            $message->to($company->email, $company->name);
            $message->subject('Apply Jobs');
            $message->attach($pathFilePDF);
        });
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
