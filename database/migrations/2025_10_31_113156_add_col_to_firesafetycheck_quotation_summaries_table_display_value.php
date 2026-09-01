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
        Schema::table('firesafetycheck_quotation_summaries', function (Blueprint $table) {
            //
            $table->integer('display_value')->default(0)->comment('印刷時に合計欄に直接表示させたい値。DBには保存しない定義だけのカラム');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('firesafetycheck_quotation_summaries', function (Blueprint $table) {
            //
            $table->dropColumn('display_value');
        });
    }
};
