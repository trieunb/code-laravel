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
            $table->engine = 'MyISAM';
            $table->increments('id');
            $table->integer('user_id');
            $table->text('company');
            $table->text('sub_title');
            $table->text('start');
            $table->text('end');
            $table->text('job_title');
            $table->text('job_description');
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
