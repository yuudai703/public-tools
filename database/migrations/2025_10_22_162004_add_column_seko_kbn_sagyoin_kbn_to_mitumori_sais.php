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
        Schema::table('mitumoriSais', function (Blueprint $table) {
            $table->string('seko_kbn')->default(1)->comment("sin:新設 teSai:撤去(再利用) te:撤去(廃棄) sai:再取付")->after('sizaiId');
            $table->string('seko_code')->default('')->comment("コンクリート打ち込みなど")->after('seko_kbn');
            $table->integer('sagyoin_kbn')->default(1)->comment("作業員区分 1:電気工事作業員 2:普通作業員 3:特殊作業員")->after('seko_kbn');
            
            $table->string("bugakari_rendo_code")->nullable()->default('')->comment("歩掛連動コード id+施工区分+施工コード+作業員区分＋")->after("sagyoin_kbn");
            $table->string("tanka_rendo_code")->nullable()->default('')->comment("単価連動コード id+施工区分")->after("sagyoin_kbn");



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mitumoriSais', function (Blueprint $table) {
                $table->dropColumn('seko_kbn');
                $table->dropColumn('sagyoin_kbn');
                $table->dropColumn('seko_code');
                $table->dropColumn('tanka_rendo_code');
                $table->dropColumn('bugakari_rendo_code');
        });
    }
};
