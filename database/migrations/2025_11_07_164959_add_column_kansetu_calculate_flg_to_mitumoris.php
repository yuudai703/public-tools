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
        Schema::table('mitumoris', function (Blueprint $table) {
            $table->boolean("kansetu_update_flg")->default(0)->after("kansetu_auto_calculate")->comment("1：最初の見積一覧で更新　2：更新しない");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mitumoris', function (Blueprint $table) {
            $table->dropColumn("kansetu_update_flg");
        });
    }
};
