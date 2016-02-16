<?php

use Illuminate\Database\Seeder;

class UsersRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = \App\Models\Role::where(['slug' => 'user'])->first();
        $user = \App\Models\User::whereDoesntHave('roles', function($q) {
            $q->where('roles.slug', 'admin');
        })->lists('id');
        $role->users()->attach(7);
    }
}
