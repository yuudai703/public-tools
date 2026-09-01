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
        Schema::table('mitumoriKos', function (Blueprint $table) {
            $table->integer('hiyo_kbn')->default(0)->after('mitumoriId')->comment('費用区分 0:通常 1:消耗品雑材 2:経費 3:間接費');
            $table->dropColumn('kansetu_flg');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mitumoriKos', function (Blueprint $table) {
            $table->dropColumn('hiyo_kbn');
            $table->boolean('kansetu_flg')->after('mitumoriId')->default(false)->comment('間接費かどうか');
        });
    }
};
