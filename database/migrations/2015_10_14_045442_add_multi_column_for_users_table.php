<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMultiColumnForUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function($table) {
            $table->string('link_profile', 100)->after('email')->nullable();
            $table->string('infomation', 100)->after('link_profile')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_work_histories', function ($table) {
            $table->dropColumn('link_profile');
            $table->dropColumn('infomation');

        });
    }
}
