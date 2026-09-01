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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string("code")->nullable(false)->default("")->comment("コード");
            $table->string("name")->nullable(false)->default("")->comment("品名");
            $table->string("kikaku")->nullable(false)->default("")->comment("規格");
            $table->string("ryutuCode")->nullable(false)->default("")->comment("流通コード");
            $table->string("torihikiKbnCode")->nullable(false)->default("")->comment("取引区分コード");
            $table->string("tosiCode")->nullable(false)->default("")->comment("都市コード");
            $table->integer("tankaKbn")->nullable(false)->comment("単価区分");
            $table->integer("tanka")->nullable(false)->default(0)->comment("単価");
            $table->integer("keisaiPege")->nullable(false)->comment("掲載ページ");
            $table->string("zei")->nullable(false)->comment("注記");
            $table->unsignedBigInteger("headerId")->nullable()->comment("ヘッダー外部キー");
            $table->timestamps();

            $table->foreign('headerId')->references('id')->on('materialHeaders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('materials');
    }
};
