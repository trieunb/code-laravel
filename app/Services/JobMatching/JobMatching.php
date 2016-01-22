<?php
namespace App\Services\JobMatching;

use App\Models\User;
use App\Models\Job;

class JobMatching
{
    private $job_id;

    public function __construct($job_id)
    {
        $this->job_id = $job_id;
    }

    public function Matcher()
    {
        $job_matching = Job::with('skills')->FindOrfail($this->job_id);
        $matcher = [
            'country' => $job_matching->country,
            'skills' => $job_matching->skills,
            'location' => $job_matching->location
        ];
        foreach ($matcher['skills'] as $value) {
            $skill_name[] = $value['title'];
        }

        $users = User::with('skills')
            ->whereHas('skills' , function($q) use ($skill_name) {
                $q->whereIn('title', $skill_name);
            })
            ->where(function($q) use ($matcher) {
                $q->where('country', $matcher['country']);
            })->get();
        return $users;
    }
}