<?php

use Illuminate\Database\Migrations\Migration;

class RemoveAliasFromDevicesTable extends Migration
{
    public function up()
    {
        Schema::table('devices', function ($table) {
            $table->dropColumn('alias');
        });
    }

    public function down()
    {
    }
}
