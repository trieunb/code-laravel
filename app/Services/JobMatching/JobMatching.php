<?php
namespace App\Services\JobMatching;

class JobMatching
{
    private $job_id;

    public function __construct($job_id)
    {
        $this->job_id = $job_id;
    }

    public function Matcher()
    {
        $job_matching = \App\Models\Job::with('skills')->FindOrFail($this->job_id);

        $matcher = [
            'country' => $job_matching->country,
            'skills' => $job_matching->skills,
            'location' => $job_matching->location
        ];
        
        foreach ($matcher['skills'] as $skill) {
            $skill_name[] = $skill['name'];
        }

        $users = \App\Models\User::with('user_skills')
            ->whereHas('user_skills' , function($q) use ($skill_name) {
                $q->whereIn('name', $skill_name);
            })
            ->where(function($q) use ($matcher) {
                $q->where('country', $matcher['country']);
            })->get();
        if ( count($users) > 0)
            $this->jobsMatching($users, $job_matching);
        return $users;
    }

    public function jobsMatching($users, $job_matching)
    {
        foreach ($users as $value) {
            $data[$value['id']] = ['read' => 1];
        }
        $job_matching->user_jobs_matching()->sync($data);
    }
}