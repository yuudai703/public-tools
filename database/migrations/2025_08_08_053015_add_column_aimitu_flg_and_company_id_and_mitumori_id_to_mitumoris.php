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
            $table->integer('companyId')->nullable()->after("gaku")->comment('会社ID');
            $table->integer('mitumoriId')->nullable()->after("gaku")->comment('見積ID(相見積もりの元となる見積ID)');
            $table->boolean('aimitu_flg')->default("0")->after("gaku")->comment('相見積もりフラグ');
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
            $table->dropColumn('companyId');
            $table->dropColumn('mitumoriId');
            $table->dropColumn('aimitu_flg');
        });
    }
};
