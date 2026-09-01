<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mitumoriSais', function (Blueprint $table) {
            $table->integer("sizaiId")->nullable()->after("kansetu_rate")->comment("選択した資材ID");
        });
    }
    
    /**
     * Reverse the migrations.
    *
    * @return void
    */
    public function down()
    {
        Schema::table('mitumoriSais', function (Blueprint $table) {
            //
            $table->dropColumn("sizaiId");
        });
    }
};
