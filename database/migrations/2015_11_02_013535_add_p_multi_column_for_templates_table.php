<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPMultiColumnForTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('templates', function($table) {
            $table->integer('cat_id')->after('user_id')->nullable();
            $table->text('template_full')->after('template')->nullable();
            $table->decimal('price')->after('template')->default(0);
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('templates', function ($table) {
            $table->dropColumn('cat_id');
            $table->dropColumn('template_full');
            $table->dropColumn('price');
        });
    }
}
