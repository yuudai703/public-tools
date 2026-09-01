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
        Schema::table('kansetu_komokus', function (Blueprint $table) {
            $table->decimal('rate', 5, 2)->default(0.00)->after("cinet_code")->comment('間接費それぞれのかけ率');
            $table->string('rate_name')->default('')->after("cinet_code")->comment('かけ率の名称');
            $table->string('j_exp')->default('')->after("cinet_code")->comment('計算方法（率以外）日本語');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kansetu_komokus', function (Blueprint $table) {
            $table->dropColumn(['rate', 'rate_name','j_exp']);
        });
    }
};
