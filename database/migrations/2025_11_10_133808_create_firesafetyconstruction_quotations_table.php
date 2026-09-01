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
        Schema::create('firesafetyconstruction_quotations', function (Blueprint $table) {
            $table->id();
            $table->string('quotation_no')->default('')->unique()->comment('見積番号');
            $table->date('quote_date')->nullable()->comment('見積日付');
            $table->integer('number')->default(0)->comment('連番 : 日付');
            $table->integer('customer_id')->default(0)->comment('取引先ID');
            $table->string('customer_name')->default('')->comment('取引先名');
            $table->string('title')->default('')->comment('件名');
            $table->integer('PIC_id')->default(0)->comment('Person in charge:担当者ID=user_id');
            $table->string('PIC_name')->default('')->comment('担当者名');
            $table->string('location')->default('御指定の場所')->comment('場所');
            $table->string('terms')->default('御打合せの上')->comment('取引方法');
            $table->bigInteger('total_extax')->default(0)->comment('税抜合計額');
            $table->bigInteger('total_tax')->default(0)->comment('税額');
            $table->string('rounding_policy')->default('四捨五入')->comment('税丸め方式:四捨五入、切上げ、切り捨て');
            $table->string('remarks')->default('')->comment('備考');
            $table->string('honorific')->default('御中')->comment('敬称');
            $table->string('expiration_date')->default('')->comment('見積有効期限:「発行より1カ月」');
          //  $table->integer('tax_cal_range')->default(0)->comment('税丸め単位:0;伝票単位,1;明細単位');
            $table->boolean('is_fixed')->default(false)->comment('確定フラグ');
            $table->integer('standard_tax_rate')->default(0)->comment('標準税率。税率変更対応。見積時の標準的な税率');
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
        Schema::dropIfExists('firesafetyconstruction_quotations');
    }
};
