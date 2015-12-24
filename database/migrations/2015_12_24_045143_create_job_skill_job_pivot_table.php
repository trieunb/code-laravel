<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJob_skillJobPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_skill_job', function (Blueprint $table) {
            $table->integer('job_skill_id')->unsigned()->index();
            $table->foreign('job_skill_id')->references('id')->on('job_skills')->onDelete('cascade');
            $table->integer('job_id')->unsigned()->index();
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
            $table->primary(['job_skill_id', 'job_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('job_skill_job');
    }
}
