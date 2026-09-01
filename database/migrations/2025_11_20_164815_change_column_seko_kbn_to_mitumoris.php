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
            $table->integer('seko_kbn')
            ->default(0)
            ->comment('0:行追加,1:新設,2:撤去(再利用),3:撤去,4:再取付')
            ->change();
            $table->integer('sagyoin_kbn')->default(0)->change();
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
            $table->string('seko_kbn')->default(1)
            ->comment('sin:新設 teSai:撤去(再利用) te:撤去(廃棄) sai:再取付')
            ->change();
            $table->integer('sagyoin_kbn')->default(1)->change();
        });
    }
};
