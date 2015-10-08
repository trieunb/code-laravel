<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserWorkHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_work_histories', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('company', 200);
            $table->string('start', 50);
            $table->string('end', 50);
            $table->string('job_title', 45);
            $table->string('job_description', 45);
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
        Schema::drop('user_work_histories');
    }
}
