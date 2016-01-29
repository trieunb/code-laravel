<?php
namespace App\Services\JobMatching;

class Matching
{
    private $job_id;

    public function __construct($job_id)
    {
        $this->job_id = $job_id;
    }

    public function match()
    {
        $job = \App\Models\Job::with('skills')->FindOrFail($this->job_id);

        $matcher = [
            'country' => $job->country,
            'skills' => $job->skills,
            'location' => $job->location
        ];
        
        foreach ($matcher['skills'] as $skill) {
            $skill_name[] = $skill['name'];
        }

        $users = \App\Models\User::whereHas('user_skills' , function($q) use ($skill_name) {
                $q->whereIn('name', $skill_name);
            })
            ->where(function($q) use ($matcher) {
                $q->where('country', $matcher['country']);
            });
        $count_user = $users->count();
        if ( $count_user > 0) {
            $total = 0;
            while ($total <= $count_user) {
                $users = $users->select('id')->skip($total)->take(100)->get();
                $this->saveJobsMatching($users, $job);
                $total += 100;
            }
        }          
    }

    public function saveJobsMatching($users, $job)
    {
        foreach ($users as $value) {
            $data[$value['id']] = ['read' => 1];
        }
        $job->user_jobs_matching()->sync($data);
    }
}