<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class applyJobsEvent extends Event
{

    private $job;
    private $company;
    private $pathFilePDF;
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($company, $job, $pathFilePDF)
    {
        $this->company = $company;
        $this->job = $job;
        $this->pathFilePDF = $pathFilePDF;
    }

    public function send()
    {
        $company = $this->company;
        $job = $this->job;
        $pathFilePDF = $this->pathFilePDF;
        \Mail::queue('emails.apply_job', compact('company', 'job'), function($message) use($company, $job, $pathFilePDF){
            $message->from(env('MAIL_USERNAME'));
            $message->to($company->email, $company->name);
            $message->subject('Apply Job' . ' - ' . $job->title);
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
