<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobs', function($table) {
            $table->text('responsibilities')->after('min_salary')->nullable();
            $table->text('requirements')->after('responsibilities')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobs', function($table) {
            $table->dropColumn('responsibilities');
            $table->dropColumn('requirements');
        });
    }
}
