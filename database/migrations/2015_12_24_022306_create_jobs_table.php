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
            $table->string('slug');
            $table->string('country')->nullable();
            $table->string('address')->nullable();
            $table->string('experience')->nullable();
            $table->text('description')->nullable();
            $table->string('salary', 100)->nullable();
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
