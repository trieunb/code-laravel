<?php

use Illuminate\Database\Seeder;
use App\Models\JobCompany;

class JobCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        JobCompany::truncate();
        factory(JobCompany::class, 20)->create();
    }
}
