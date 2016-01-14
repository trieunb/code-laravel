<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ApplyJobsEvent extends Event
{

    private $user;
    private $job;
    private $company;
    private $template;
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user, $company, $job, $template)
    {
        $this->user = $user;
        $this->company = $company;
        $this->job = $job;
        $this->template = $template;
    }

    public function send()
    {
        $user = $this->user;
        $company = $this->company;
        $job = $this->job;
        $template = $this->template;
        $pathFilePDF = public_path($template->source_file_pdf);
        \Mail::queue('emails.apply_job', ['user' => $user, 'company' => $company, 'job' => $job], 
            function($message) use($company, $job, $pathFilePDF, $template){
                $message->from(env('MAIL_USERNAME'));
                $message->to($company->email, $company->name);
                $message->subject('Resume Builder' . ' - ' . $job->title);
                $message->attach($pathFilePDF, ['as' => $template->title]);
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
