<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $this->call(QuestionSeeder::class);
        $this->call(ReferenceTableSeeder::class);
        $this->call(ObjectiveTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(RoleTableSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(UserEducationTableSeeder::class);
        $this->call(UserSKillTableSeeder::class);
        $this->call(UserWorkHistoryTableSeeder::class);

        Model::reguard();
    }
}
