<?php
namespace App\Services\JobMatching;

class JobMatching
{
    private $job_id;

    public function __construct($job_id)
    {
        $this->job_id = $job_id;
    }

    public function Matcher(array $models)
    {
        $job_matching = $models['job']::with('skills')->FindOrfail($this->job_id);

        $matcher = [
            'country' => $job_matching->country,
            'skills' => $job_matching->skills,
            'location' => $job_matching->location
        ];
        
        $param = ["address" => $matcher['location']];
        $response = \Geocoder::geocode('json', $param);
        // return $matcher['location'];
        return json_decode($response, true);

        foreach ($matcher['skills'] as $skill) {
            $skill_name[] = $skill['title'];
        }

        $users = $models['user']::with('skills')
            ->whereHas('skills' , function($q) use ($skill_name) {
                $q->whereIn('title', $skill_name);
            })
            ->where(function($q) use ($matcher) {
                $q->where('country', $matcher['country']);
            })->get();
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