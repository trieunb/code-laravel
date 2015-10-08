<?php

use Illuminate\Database\Seeder;

class UserSKillTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\UserSkill::class, 20)->create();
    }
}
