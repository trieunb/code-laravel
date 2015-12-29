<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
/**
 * Data seeder for jobs
 */
class JobDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $this->call(JobCategorySeeder::class);
        $this->call(JobSkillSeeder::class);
        $this->call(JobCompanySeeder::class);
        $this->call(JobsTableSeeder::class);
        Model::reguard();
    }
}
