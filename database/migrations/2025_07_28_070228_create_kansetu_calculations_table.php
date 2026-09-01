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
        Schema::create('kansetu_calculations', function (Blueprint $table) {
            $table->id();
            $table->integer('mitumoriId');
            $table->string('kansetu_bun_code')->nullable()->comment('何の間接費分類を使ったか');
            $table->string('kansetu_code')->nullable()->comment('何の間接費を使ったか');
            $table->string('name')->default("")->comment('間接費名称');
            $table->decimal('kansetu_rate', 5, 2)->default(0.00)->comment('間接費のかけ率');
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
        Schema::dropIfExists('kansetu_calculations');
    }
};
