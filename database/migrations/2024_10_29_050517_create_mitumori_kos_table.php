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
        Schema::create('mitumoriKos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("mitumoriId")->nullable(false)->comment("見積ID");
            $table->integer("gyoNo")->comment("行ナンバー");
            $table->string("name")->default("")->comment("名");
            $table->integer("su")->default(0)->comment("数量");
            $table->string("tani")->default("")->comment("単位");
            $table->integer("tanka")->default(0)->comment("単価");
            $table->integer("gaku")->default(0)->comment("金額");
            $table->string("biko")->default("")->comment("備考");
            $table->double("bugakari")->default(0)->comment("歩掛");
            $table->boolean("nomberFlg")->default(false)->comment("ナンバーフラグ");
            $table->string("showNo")->nullable(true)->comment("表示番号");
            $table->integer("kaiLevel")->nullable(true)->comment("階層レベル");
            $table->integer("kensetuhiBunrui")->comment("建設費分類");
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
        Schema::dropIfExists('mitumoriKos');
    }
};
