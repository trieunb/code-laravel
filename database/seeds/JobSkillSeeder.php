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
            'Java',
            'C#',
            'Php',
            '.Net',
            'C++',
            'Object c',
            'Oracle',
            'Amazon service',
            'Mysql',
            'Ruby',
            'English',
            'SQL',
            'IOS',
            'Android',
            'Windowphone',
            'Other'
        ];

        foreach ($data as $key => $value) {
            $job_skill = new JobSkill;
            $job_skill->title = $value;
            $job_skill->save();
        }
    }
}
