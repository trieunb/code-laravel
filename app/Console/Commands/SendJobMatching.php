<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\AbstractRepository;
use App\Repositories\Job\JobRepository;
use App\Repositories\User\UserInterface;
use App\Models\Job;
use App\Models\User;
use \Carbon\Carbon;

class SendJobMatching extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:job_matching';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'push notification job matching to user';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    protected $job_matching;
    protected $user;
    public function __construct(JobRepository $job_matching, UserInterface $user)
    {
        parent::__construct();
        $this->job_matching = $job_matching;
        $this->user = $user;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $start = (new Carbon('now'))->hour(0)->minute(0)->second(0);
        $end = (new Carbon('now'))->hour(23)->minute(59)->second(59);
        $users = $this->job_matching->make(['user_jobs_matching'])->get();

        foreach ($users as $user) {
            if ( count($user->user_jobs_matching) > 0) {
                foreach ($user->user_jobs_matching as $job_match) {
                    if ($job_match->pivot->created_at >= $start && $job_match->created_at <= $end)
                        $notifi_jobs_match[] = $job_match->id;
                }
            }
        }
        if ( !isset($notifi_jobs_match)){ 
            $message = "User not found";
        } else {
            foreach (array_unique($notifi_jobs_match) as $key => $value) {
                $notifi_user = $this->user->getById($value);
                if ( !$notifi_user->device) {
                    $message = "User device not found!";
                } else {
                    $this->notifJobMatch($notifi_user);
                    $message = "Notification send";
                }
            }
        }
        dd($message);
        return redirect()->back()->with('message', $message);
        
    }

    public function notifJobMatch($notifi_user)
    {
        $notifOptions = [
            'data' => [
                'type' => 'jobs_match'
            ]
        ];

        $notif = new \App\Services\PushNotif\Notification(
            $notifi_user->device,
            "We found jobs suitable for you",
            $notifOptions
        );
        return $notif->push();

    }
}
