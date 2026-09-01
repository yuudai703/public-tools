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
            $table->integer('rom_gaku')->default(0)->after("gaku")->comment('労務単価の合計金額');
            $table->integer('sizai_gaku')->default(0)->after("gaku")->comment('資材だけの合計金額 消耗品雑材も含む');
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
            $table->dropColumn('rom_gaku');
            $table->dropColumn('sizai_gaku');
        });
    }
};
