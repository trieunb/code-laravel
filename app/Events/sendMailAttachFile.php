<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class sendMailAttachFile extends Event
{
    private $user;
    private $pathFileWord;
    private $pathFilePDF;

    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user, $pathFileWord, $pathFilePDF)
    {
        $this->user = $user;
        $this->pathFileWord = $pathFileWord;
        $this->pathFilePDF = $pathFilePDF;
    }

    public function send()
    {
        $user = $this->user;
        $pathFileWord = $this->pathFileWord;
        $pathFilePDF = $this->pathFilePDF;
       \Log::info('send mail', [$pathFilePDF]);
        \Mail::queue('emails.send_attach_file', compact('user'), function($message) use($user, $pathFileWord, $pathFilePDF){
            $message->from(env('MAIL_USERNAME'));
            $message->to($user->email, $user->first_name.' '.$user->lastname);
            $message->subject('Send Attach File');
            // $message->attach($pathFileWord);
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
