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
        Schema::create('kikaku_name_for_mitumoris', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("mitumoriId")->nullable()->comment("対象の見積ID");
            $table->bigInteger("kikakuId")->nullable()->comment("対象の規格ID");
            $table->string("name")->nullable();
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
        Schema::dropIfExists('sizai_kikaku_for_mitumoris');
    }
};
