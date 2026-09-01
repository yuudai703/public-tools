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
        Schema::create('mitumoriSais', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("mitumoriKoId")->nullable(false)->comment("見積項目ID");
            $table->integer("gyoNo")->default(0);
            $table->string("hinCode")->default("");
            $table->string("hinName")->default("");
            $table->string("siyo")->default("")->comment("仕様");
            $table->integer("su")->default(0)->comment("数量");
            $table->integer("tanka")->default(0)->comment("単価");
            $table->integer("gaku")->default(0)->comment("金額");
            $table->string("biko")->default("")->comment("備考");
            $table->string("toso")->default("")->comment("塗装");
            $table->double("bugakari")->default(0)->comment("歩掛");
            $table->integer("kansetuhiBunrui")->default(0)->comment("建設費分類");
            $table->double("hokyuritu")->default(0)->comment("補給率");
            $table->double("huzokuritu1")->default(0)->comment("付属率1");
            $table->double("huzokuritu2")->default(0)->comment("付属率2");
            $table->double("huzokuritu3")->default(0)->comment("付属率3");
            $table->double("zatuzairitu")->default(0)->comment("雑材率");
            $table->double("sonotaritu")->default(0)->comment("その他率");
            $table->double("size1")->default(0);
            $table->double("size2")->default(0);
            $table->double("size3")->default(0);
            $table->integer("chengeCost")->default(0)->comment("取替費");
            $table->integer("disposalCost")->default(0)->comment("処分費");
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
        Schema::dropIfExists('mitumoriSais');
    }
};
