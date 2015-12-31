<?php

use Illuminate\Database\Seeder;
use App\Models\Job;

class JobsTableSeeder extends Seeder
{
    public function run()
    {
        Job::truncate();
        $jobs = factory(Job::class, 50)->create();
        $skillIds = \DB::table('job_skills')->lists('id');
        \DB::table('job_skill_pivot')->truncate();
        if (count($skillIds)) {
            // Tag some skills
            foreach ($jobs as $job) {
                $job->skills()->sync([
                    $skillIds[array_rand($skillIds)]
                ]);
            }
        }
    }
}
