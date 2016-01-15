<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Models\JobCompany;

class ApplyJobsEvent extends Event
{

    private $user;
    private $job;
    private $template;
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user, $job, $template)
    {
        $this->user = $user;
        $this->job = $job;
        $this->template = $template;
    }

    public function send()
    {
        $user = $this->user;
        $job = $this->job;
        $template = $this->template;
        $company = JobCompany::FindOrfail($job->company_id);
        $pathFilePDF = public_path($template->source_file_pdf);
        \Mail::queue('emails.apply_job', ['user' => $user, 'job' => $job], 
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
