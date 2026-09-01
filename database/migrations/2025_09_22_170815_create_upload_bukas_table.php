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
        Schema::create('uploadBukas', function (Blueprint $table) {
            $table->id();
            $table->string("kenbutu_code")->nullable(false);
            $table->double("tanka1")->nullable(false);
            $table->string("tani_code")->nullable(false);
            $table->date("ym")->nullable(false);
            $table->timestamps();
            $table->index('kenbutu_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('uploadBukas');
    }
};
