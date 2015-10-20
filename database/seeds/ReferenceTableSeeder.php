<?php

use App\Models\Reference;
use Illuminate\Database\Seeder;

class ReferenceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Reference::class, 20)->create();
    }
}
