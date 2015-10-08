<?php

use Illuminate\Database\Seeder;

class UserEducationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\UserEducation::class, 20)->create();
    }
}
