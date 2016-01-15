<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class sendMailAttachFile extends Event
{
    private $user;
    private $pathFilePDF;

    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user, $pathFilePDF)
    {
        $this->user = $user;
        $this->pathFilePDF = $pathFilePDF;
    }

    public function send()
    {
        $user = $this->user;
        $pathFilePDF = $this->pathFilePDF;
        \Mail::queue('emails.send_attach_file', compact('user'), function($message) use($user, $pathFilePDF){
            $message->to($user->email, $user->first_name.' '.$user->lastname);
            $message->subject('ResumeBuilder - Your resume');
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
