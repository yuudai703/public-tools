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
            $table->integer("kensetuhiBunrui")->default(0)->change()->comment("建設費分類");
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
            $table->integer("kensetuhiBunrui")->change()->comment("建設費分類");
        });
    }
};
