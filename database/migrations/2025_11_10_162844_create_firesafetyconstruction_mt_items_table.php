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
        Schema::create('firesafetyconstruction_mt_items', function (Blueprint $table) {
            $table->id();
            $table->integer('cat_id');
            $table->integer('seq')->default(0);
            $table->string('name')->default('');
            $table->string('spec')->default('');
            $table->string('unit')->default('');
            $table->integer('unit_price')->default(0);
            $table->string('remarks')->default('')->comment('選択時に反映されないマスタ上だけのメモ');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('firesafetyconstruction_mt_items');
    }
};
