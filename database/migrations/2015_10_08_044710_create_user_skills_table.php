<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_skills', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('skill_name', 45);
            $table->boolean('skill_test');
            $table->string('skill_test_point', 45);
            $table->String('experience', 45);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_skills');
    }
}
