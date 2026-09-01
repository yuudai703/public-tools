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
        Schema::table('gen_quatations', function (Blueprint $table) {
            //
            $table->integer('standard_tax_rate')->change()->default(0)->comment('標準税率。税率変更対応。見積時の標準的な税率');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gen_quatations', function (Blueprint $table) {
            //
            $table->dropColumn('standard_tax_rate');
        });
    }
};
