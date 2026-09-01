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
        Schema::create('firesafetyconstruction_quotation_summaries', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->default(0)->index()->comment('見積（親）のID');
            $table->integer('rownumber')->default(0)->comment('行番号');
            $table->integer('cat_id')->default(0)->comment('項目区分ID');
            $table->integer('item_id')->default(0)->comment('資材マスタID');
            $table->string('name')->default('')->comment('名前(項目）');
            $table->string('spec')->default('')->comment('仕様');
            $table->double('qty', 8, 2)->nullable()->comment('数量');
            $table->string('unit')->default('')->comment('単位');
            $table->integer('unit_price')->default(0)->comment('単価');
            $table->integer('price')->default(0)->comment('金額');
            $table->integer('indirect_expense_id')->default(0)->comment('間接項目ID');
            $table->string('remarks')->default('')->comment('備考 : フリー入力兼用');
            $table->boolean('is_free_input')->default(false)->comment('行全体を備考欄として利用');
            $table->boolean('is_disp_details')->default(false)->comment('内訳リンク表示:項目区分の場合のみ');
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
        Schema::dropIfExists('firesafetyconstruction_quotation_summaries');
    }
};
