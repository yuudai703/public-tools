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
        Schema::create('firesafetycheck_quotation_details', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->default(0)->index()->comment('見積（親）のID');
            $table->integer('summary_id')->default(0)->comment('総括ID');
            $table->integer('rownumber')->default(0)->comment('行番号');
            $table->integer('item_id')->default(0)->comment('項目ID');
            $table->string('name')->default('')->comment('名前(項目)');
            $table->double('qty', 8, 2)->nullable()->comment('数量');
            $table->string('unit')->default('')->comment('単位');
            $table->integer('device_inspection_unit_expense')->default(0)->comment('機器点検金額');
            $table->integer('general_inspection_unit_expense')->default(0)->comment('総合点検金額');
            $table->integer('device_inspection_expense')->default(0)->comment('機器点検金額');
            $table->integer('general_inspection_expense')->default(0)->comment('総合点検金額');
            $table->string('remarks')->default('')->comment('摘要 : フリー入力でしか使わない');
            $table->boolean('is_free_input')->default(false)->comment('行全体を備考欄として利用');
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
        Schema::dropIfExists('firesafetycheck_quotation_details');
    }
};
