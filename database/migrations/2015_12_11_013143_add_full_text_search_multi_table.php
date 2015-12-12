<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFullTextSearchMultiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE user_educations ADD FULLTEXT search(school_name, degree, result)');
        DB::statement('ALTER TABLE user_skills ADD FULLTEXT search(skill_name, experience)');
        DB::statement('ALTER TABLE user_work_histories ADD FULLTEXT search(company, job_title, job_description)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_educations', function($table) {
            $table->dropIndex('search');
        });
        Schema::table('user_skills', function($table) {
            $table->dropIndex('search');
        });
        Schema::table('user_work_histories', function($table) {
            $table->dropIndex('search');
        });

    }
}
