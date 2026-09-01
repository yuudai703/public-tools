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
            $table->decimal('kansetu_rate', 5, 2)->default(0.00)->after("kansetu_flg")->comment('間接費のかけ率');
            $table->string('kansetu_bun_code')->nullable()->after("kansetu_flg")->comment('何の間接費分類を使ったか');
            $table->string('kansetu_code')->nullable()->after("kansetu_flg")->comment('何の間接費を使ったか');
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
            $table->dropColumn(['kansetu_rate', 'kansetu_bun_code', 'kansetu_code']);
        });
    }
};
