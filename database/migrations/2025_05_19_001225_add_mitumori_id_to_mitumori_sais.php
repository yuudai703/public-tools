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
            $table->biginteger("mitumoriId")->nullable(true);
            $table->boolean("kansetu_flg")->default(false)->comment("間接費フラグ 簡易見積書のみ仕様する");
            $table->biginteger("mitumoriKoId")->nullable(true)->change();
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
            $table->integer("mitumoriId")->change();
            $table->dropColumn("mitumoriId");
            $table->dropColumn("kansetu_flg");
            $table->biginteger("mitumoriKoId")->change();
        });
    }
};
