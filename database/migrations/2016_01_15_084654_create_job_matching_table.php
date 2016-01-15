<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobMatchingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_matching', function(Blueprint $table) {
            $table->integer('user_id')->unsigned()->index();
            $table->integer('job_id')->unsigned()->index();
            $table->primary(['user_id', 'job_id']);
            $table->boolean('read')->default(0);
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
        Schema::drop('job_matching');
    }
}
