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
            $table->text('skill_name');
            $table->boolean('skill_test');
            $table->text('skill_test_point');
            $table->text('experience');
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
