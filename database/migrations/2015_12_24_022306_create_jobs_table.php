<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('job_cat_id');
            $table->integer('user_id');
            $table->string('title');
            $table->string('slug');
            $table->string('company_name');
            $table->string('skill');
            $table->string('country');
            $table->string('experience')->nullable();
            $table->text('description')->nullable();
            $table->string('salary')->nullable();
            $table->string('logo')->nullable();
            $table->timestamps()
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('jobs');
    }
}
