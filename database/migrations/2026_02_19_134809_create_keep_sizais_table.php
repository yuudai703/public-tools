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
        Schema::create('keep_sizais', function (Blueprint $table) {
            $table->id();
            $table->string("sekoCode")->nullable()->nullable(false);
            $table->string("bugakariColumn")->nullable(false);
            // $table->string("sekoSyu")->nullable(false);
            $table->string("sizaiCode")->nullable(false);
            $table->bigInteger("mitumoriId")->nullable();
            $table->bigInteger("mitumoriKoId")->nullable();
            $table->bigInteger("kikakuId")->nullable();
            $table->integer("su")->nullable(false);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
        DB::statement("ALTER TABLE `keep_sizais` comment '資材追加ウィンドで資材数を変更したときに一時的に保存するテーブル'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('keep_sizais');
    }
};
