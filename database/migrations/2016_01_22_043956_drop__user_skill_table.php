<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropUserSkillTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('user_skills');
        Schema::create('user_skills', function(Blueprint $table) {
            $table->integer('user_id')->unsigned()->index();
            $table->integer('job_skill_id')->unsigned()->index();
            $table->string('level', 100)->nullable();
            $table->primary(['user_id', 'job_skill_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
