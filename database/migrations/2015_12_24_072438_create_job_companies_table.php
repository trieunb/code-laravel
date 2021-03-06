<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_companies', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('country')->nullable();
            $table->string('address')->nullable();
            $table->string('website')->nullable();
            $table->string('logo')->nullable();
            $table->text('description')->nullable();
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
        Schema::table('job_companies', function($table) {
            $table->dropIndex('search');
        });
        
        Schema::drop('job_companies');
    }
}
