<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

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

        $this->call(UserTableSeeder::class);

        Model::reguard();
    }
}

class UserTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'name' => 'Nguyen van Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('123123')
            ],
            [
                'name' => 'Nguyen Van User',
                'email' => 'user@gmail.com',
                'password' => Hash::make('123123')
            ],
            [
                'name' => 'Nguyen Ba Trieu',
                'email' => 'trieunb08@gmail.com',
                'password' => Hash::make('123123')
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
