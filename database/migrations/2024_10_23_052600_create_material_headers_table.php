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
        Schema::create('materialHeaders', function (Blueprint $table) {
            $table->id();
            $table->date("operationYm")->nullable(false)->comment("運用年月");
            $table->string("tosiCode")->nullable(false)->comment("提供都市");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('materialHeaders');
    }
};
