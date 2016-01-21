<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToJobCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_companies', function($table) {
            $table->text('overview')->after('description')->nullable();
            $table->text('benefits')->after('overview')->nullable();
            $table->string('registration_no')->after('benefits')->nullable();
            $table->string('industry')->after('registration_no')->nullable();
            $table->string('company_size')->after('industry')->nullable();
            $table->text('why_join_us')->after('company_size')->nullable();
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
            $table->dropColumn('overview');
            $table->dropColumn('benefits');
            $table->dropColumn('registration_no');
            $table->dropColumn('industry');
            $table->dropColumn('company_size');
            $table->dropColumn('why_join_us');
        });
    }
}
