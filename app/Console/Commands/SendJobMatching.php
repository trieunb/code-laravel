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

        $devices = \DB::table('devices')
            ->whereExists(function ($query) {
                $query->select(\DB::raw(1))
                      ->from('job_matching')
                      ->whereBetween('job_matching.created_at', [
                            (new \Carbon\Carbon('now'))->startOfDay(),
                            (new \Carbon\Carbon('now'))->endOfDay()])
                      ->whereRaw('job_matching.user_id = devices.user_id');
            })
            ->get();

        if ( count($devices) <= 0){ 
            $message = "User device not found!";
        } else {
            foreach ($devices as $device) {
                $this->notifJobMatch($device);
                $message = "Notification send";
            }
        }
        dd($message);
          
    }

    public function notifJobMatch($device)
    {
        $notifOptions = [
            'custom' => [ // For IOS
                'data' => [
                    'type' => 'jobs_match'
                ]
            ], // For Android
            'data' => [
                'type' => 'jobs_match'
            ]
        ];

        $notif = new \App\Services\PushNotif\Notification(
            $device,
            "We found jobs suitable for you",
            $notifOptions
        );
        $notif->push();

    }
}
