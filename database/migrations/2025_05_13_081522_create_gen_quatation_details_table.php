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
        Schema::create('gen_quatation_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('parent_id')->default(0)->index()->comment('見積（親）のID');
            $table->integer('rownumber')->default(0)->comment('行番号');
            $table->string('symbol')->default('')->comment('行符号');
            $table->string('name1')->default('')->comment('名前(品目)');
            $table->string('name2')->default('')->comment('名前(規格)　納品見積用');
            $table->double('qty', 8, 2)->nullable()->comment('数量');
            $table->string('unit')->default('')->comment('単位');
            $table->double('unit_price', 8, 2)->nullable()->comment('単価');
            $table->double('price', 8, 2)->nullable()->comment('金額');
            $table->string('remarks')->default('')->comment('摘要');
            $table->integer('tax_rate')->default(0)->comment('税率');
            $table->integer('tax')->default(0)->comment('税額:明細毎丸めの場合');
            $table->boolean('is_level_title')->default(false)->comment('階層タイトル行の当否');
            $table->boolean('is_show_level_total')->default(true)->comment('階層の小計表示有無');
            $table->integer('level_id')->default(0)->comment('階層要素の場合、帰属する階層タイトル行のid');
            $table->boolean('is_free_input')->default(false)->comment('行全体を備考欄として利用');
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
        Schema::dropIfExists('gen_quatation_details');
    }
};
