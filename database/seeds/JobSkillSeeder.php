<?php

use Illuminate\Database\Seeder;
use App\Models\JobSkill;

class JobSkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        JobSkill::truncate();
        $data = [
            'java',
            'c#',
            'php',
            '.net',
            'c++',
            'object c',
            'oracle',
            'amazon service',
            'mysql',
            'ruby',
            'english',
            'sql',
            'ios',
            'android',
            'windowphone',
            'other'
        ];

        foreach ($data as $key => $value) {
            $job_skill = new JobSkill;
            $job_skill->title = $value;
            $job_skill->save();
        }
    }
}
