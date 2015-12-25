<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeEngineTableJobsAndCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobs', function($table) {
            $table->engine = 'MyISAM';
        });

        Schema::table('job_companies', function($table) {
            $table->engine = 'MyISAM';
        });

        DB::statement('ALTER TABLE jobs ADD FULLTEXT search(title, experience, description)');
        DB::statement('ALTER TABLE job_companies ADD FULLTEXT search(name, description)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobs', function($table) {
            $table->dropIndex('search');
        });
        
        Schema::table('job_companies', function($table) {
            $table->dropIndex('search');
        });
    }
}
