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

        $users = $this->user->make(['jobs_matching' => function($q) {
            $q->whereBetween('job_matching.created_at', [
                (new \Carbon\Carbon('now'))->startOfDay(), 
                (new \Carbon\Carbon('now'))->endOfDay()]);
        }])->has('jobs_matching')->get();

        foreach ($users as $user) {
            if ( count($user->jobs_matching) > 0) {
                foreach ($user->jobs_matching as $job_match) {
                    $notifi_jobs_match[] = $job_match->pivot->user_id;
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
        return response()->json([
            'status_code' => 200,
            'status' => true, 
            'message' => $message]);
        
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
