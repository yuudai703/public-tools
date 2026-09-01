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
            $table->double("bugakariTo")->default(0)->after("bugakari")->comment("特殊作業員作業員歩掛（土木など）");
            $table->double("bugakariDe")->default(0)->after("bugakari")->comment("電気作業員歩掛");
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
            $table->dropColumn(["bugakariDe", "bugakariTo"]);
        });
    }
};
