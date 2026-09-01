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
        Schema::create('kihons', function (Blueprint $table) {
            $table->id();
            $table->double("first_de_tax")->nullable()->default(0)->comment("電気積算消費税");
            $table->double("first_se_tax")->nullable()->default(0)->comment("設備積算消費税");
            $table->string("first_de_keisyo")->nullable()->default("")->comment("電気敬称");
            $table->string("first_se_keisyo")->nullable()->default("")->comment("設備敬称");
            $table->integer("first_de_rom")->nullable()->default(0)->comment("電気労務単価");
            $table->integer("first_rom")->nullable()->default(0)->comment("労務単価");
            $table->integer("first_to_rom")->nullable()->default(0)->comment("特殊労務単価");
            $table->integer("first_se_rom")->nullable()->default(0)->comment("設備労務単価");
            $table->timestamp('created_at')->useCurrent()->nullable(false);
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kihons');
    }
};
