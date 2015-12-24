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
            $table->integer('company_id');
            $table->string('title');
            $table->string('country')->nullable();
            $table->string('location')->nullable();
            $table->string('experience')->nullable();
            $table->text('description')->nullable();
            $table->integer('min_salary')->nullable()->default(0);
            $table->json('images')->nullable();
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
