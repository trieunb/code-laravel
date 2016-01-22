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
        return $job_matching = Job::with('skills')->FindOrfail($this->job_id);
    }
}