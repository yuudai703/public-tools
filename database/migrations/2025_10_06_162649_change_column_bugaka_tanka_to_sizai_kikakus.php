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
            $table->double("bugakaA01")->default(0)->nullable()->change();
            $table->double("bugakaA02")->default(0)->nullable()->change();
            $table->double("bugakaA03")->default(0)->nullable()->change();
            $table->double("bugakaA04")->default(0)->nullable()->change();
            $table->double("bugakaA05")->default(0)->nullable()->change();
            $table->double("bugakaA06")->default(0)->nullable()->change();
            $table->double("bugakaA07")->default(0)->nullable()->change();
            $table->double("bugakaA08")->default(0)->nullable()->change();
            $table->double("bugakaA09")->default(0)->nullable()->change();
            $table->double("bugakaA10")->default(0)->nullable()->change();
            $table->double("bugakaA11")->default(0)->nullable()->change();
            $table->double("bugakaA12")->default(0)->nullable()->change();
            $table->double("bugakaA13")->default(0)->nullable()->change();
            $table->double("bugakaA14")->default(0)->nullable()->change();
            $table->double("bugakaA15")->default(0)->nullable()->change();
            $table->double("bugakaB01")->default(0)->nullable()->change();
            $table->double("bugakaB02")->default(0)->nullable()->change();
            $table->double("bugakaB03")->default(0)->nullable()->change();
            $table->double("bugakaB04")->default(0)->nullable()->change();
            $table->double("bugakaB05")->default(0)->nullable()->change();
            $table->double("bugakaB06")->default(0)->nullable()->change();
            $table->double("bugakaB07")->default(0)->nullable()->change();
            $table->double("bugakaB08")->default(0)->nullable()->change();
            $table->double("bugakaB09")->default(0)->nullable()->change();
            $table->double("bugakaB10")->default(0)->nullable()->change();
            $table->double("bugakaB11")->default(0)->nullable()->change();
            $table->double("bugakaB12")->default(0)->nullable()->change();
            $table->double("bugakaB13")->default(0)->nullable()->change();
            $table->double("bugakaB14")->default(0)->nullable()->change();
            $table->double("bugakaB15")->default(0)->nullable()->change();
            $table->double("tanka1")->default(0)->nullable()->change();
            $table->double("tanka2")->default(0)->nullable()->change();
            $table->double("tanka3")->default(0)->nullable()->change();
            $table->double("tanka4")->default(0)->nullable()->change();
            $table->double("tanka5")->default(0)->nullable()->change();
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
            $table->double("bugakaA01")->nullable();
            $table->double("bugakaA02")->nullable();
            $table->double("bugakaA03")->nullable();
            $table->double("bugakaA04")->nullable();
            $table->double("bugakaA05")->nullable();
            $table->double("bugakaA06")->nullable();
            $table->double("bugakaA07")->nullable();
            $table->double("bugakaA08")->nullable();
            $table->double("bugakaA09")->nullable();
            $table->double("bugakaA10")->nullable();
            $table->double("bugakaA11")->nullable();
            $table->double("bugakaA12")->nullable();
            $table->double("bugakaA13")->nullable();
            $table->double("bugakaA14")->nullable();
            $table->double("bugakaA15")->nullable();
            $table->double("bugakaB01")->nullable();
            $table->double("bugakaB02")->nullable();
            $table->double("bugakaB03")->nullable();
            $table->double("bugakaB04")->nullable();
            $table->double("bugakaB05")->nullable();
            $table->double("bugakaB06")->nullable();
            $table->double("bugakaB07")->nullable();
            $table->double("bugakaB08")->nullable();
            $table->double("bugakaB09")->nullable();
            $table->double("bugakaB10")->nullable();
            $table->double("bugakaB11")->nullable();
            $table->double("bugakaB12")->nullable();
            $table->double("bugakaB13")->nullable();
            $table->double("bugakaB14")->nullable();
            $table->double("bugakaB15")->nullable();
            $table->double("tanka1")->nullable();
            $table->double("tanka2")->nullable();
            $table->double("tanka3")->nullable();
            $table->double("tanka4")->nullable();
            $table->double("tanka5")->nullable();
        });
    }
};
