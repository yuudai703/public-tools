<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up()
    {
        Schema::table('mitumoriKos', function (Blueprint $table) {
            $table->string("bugakariCode")->default('')->comment("歩掛コード");
        });
    }

    
    public function down()
    {
        Schema::table('mitumoriKos', function (Blueprint $table) {
            $table->dropColumn("bugakariCode");
        });
    }
};
