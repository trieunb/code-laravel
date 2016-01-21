<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToUserSkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_skills', function($table) {
            $table->integer('job_skill_id')->after('user_id');
            $table->string('title')->after('job_skill_id');
            $table->string('slug')->after('title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_skills', function($table) {
            $table->dropColumn('job_skill_id');
            $table->dropColumn('title');
            $table->dropColumn('slug');
        });
    }
}
