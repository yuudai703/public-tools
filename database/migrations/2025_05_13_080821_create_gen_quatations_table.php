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
        Schema::create('gen_quatations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('quatation_no')->default('')->unique()->comment('見積番号');
            $table->integer('quat_cat')->default(0)->comment('見積区分 (納品見積など想定)');
            $table->integer('dept_id')->default(0)->comment('部署ID');
            $table->date('quate_date')->nullable()->comment('見積日付');
            $table->integer('number')->default(0)->comment('連番 : 部署＋日付');
            $table->integer('customer_id')->default(0)->comment('取引先ID');
            $table->string('customer_name')->default('')->comment('取引先名');
            $table->string('title')->default('')->comment('件名');
            $table->integer('PIC_id')->default(0)->comment('Person in charge:担当者ID=user_id');
            $table->string('PIC_name')->default('')->comment('担当者名');
            $table->double('total_extax', 8, 2)->default(0)->comment('税抜合計額');
            $table->double('total_tax', 8, 2)->default(0)->comment('税額');
            $table->integer('standard_tax_rate')->default(0)->comment('標準税率。税率変更対応。');
            $table->string('rounding_policy')->default('四捨五入')->comment('税丸め方式:四捨五入、切上げ、切り捨て');
            $table->string('remarks')->default('')->comment('備考');
            $table->string('expiration_date')->default('')->comment('見積有効期限:「発行より1カ月」');
            $table->integer('tax_cal_range')->default(0)->comment('税丸め単位:0;伝票単位,1;明細単位');
            $table->string('conditions')->default('')->comment('（納品見積）取引条件');
            $table->string('trading_place')->default('')->comment('（納品見積）取引場所');
            $table->string('excluded_cost')->default('')->comment('（納品見積）対象外費用：運送費含まず/含む 等');
            $table->boolean('is_fixed')->default(false)->comment('確定フラグ');
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
        Schema::dropIfExists('gen_quatations');
    }
};
