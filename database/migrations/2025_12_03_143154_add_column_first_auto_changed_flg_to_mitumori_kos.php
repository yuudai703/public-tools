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
            $table->boolean('first_auto_changed_flg')->default(false)->after('kansetu_auto_calculate')->comment('初回自動計算後変更フラグ 初めての時は自動計算内容を映す');
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
            $table->dropColumn('first_auto_changed_flg');
        });
    }
};
