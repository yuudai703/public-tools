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
        Schema::table('sizai_kikakus', function (Blueprint $table) {
            $table->bigInteger("mitumoriId")->nullable()->default(null)->comment("対象の見積ID")->after("gyoNo");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sizai_kikakus', function (Blueprint $table) {
            $table->dropColumn("mitumoriId");
        });
    }
};
