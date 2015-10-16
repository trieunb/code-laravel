<?php

use Illuminate\Database\Seeder;

class ObjectiveTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Objective::class, 20)->create();
    }
}
