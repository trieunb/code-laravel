<?php

use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = factory(App\Models\User::class, 1)->make();
        $user->email = 'admin@example.com';
        $user->save();
        $user->roles()->attach(
            App\Models\Role::where(['slug' => 'admin'])->first());
        echo "[INFO] Created admin user: " . $user->email ."\n";
    }
}
