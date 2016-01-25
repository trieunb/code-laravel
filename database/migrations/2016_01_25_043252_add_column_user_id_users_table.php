<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnUserIdUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_skills', function($table) {
            $table->integer('user_id')->nullable()->after('id');
            $table->string('level', 100)->nullable()->after('slug');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('job_skills', function($table) {
            $table->dropColumn('user_id');
            $table->dropColumn('level');
        });
    }
}
