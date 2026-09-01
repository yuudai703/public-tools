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
        Schema::create('mitumoris', function (Blueprint $table) {
            $table->id();
            $table->integer("groupId")->nullable(true)->comment("");
            $table->string("clientName")->default("")->comment("相手先");
            $table->string("title")->default("")->comment("工事件名");
            $table->integer("tantoId")->nullable(true)->comment("担当者ID");
            $table->string("area")->default("")->comment("施工場所");
            $table->string("torihikiho")->default("")->comment("取引方法");
            $table->string("kigen")->default("")->comment("有効期限");
            $table->string("biko")->default("")->comment("備考");
            $table->integer("gaku")->default(0)->comment("金額");
            $table->datetime("print_at")->nullable(true)->comment("印刷日時");
            $table->boolean("kokyoF")->default(false)->comment("公共F");
            $table->integer("kokiM")->nullable(true)->comment("公共月");
            $table->string("type")->comment("タイプ");
            $table->integer("loginId")->comment("ログインID");
            $table->datetime("loginTime")->comment("ログインタイム");
            $table->string("keisyo")->default("")->comment("敬称");
            $table->string("memo")->default("")->comment("メモ");
            $table->integer("romutan")->default(0)->comment("労務単価");
            $table->integer("syukusyaku")->default(0)->comment("拾い縮尺");
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
        Schema::dropIfExists('mitumoris');
    }
};
