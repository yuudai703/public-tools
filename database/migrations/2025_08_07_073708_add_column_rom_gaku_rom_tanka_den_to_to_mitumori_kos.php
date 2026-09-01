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
        Schema::table('mitumoriKos', function (Blueprint $table) {
            $table->integer('rom_tankaTo')->default(0)->after("rom_tanka")->comment('特殊作業員労務単価（土木など）');
            $table->integer('rom_tankaDe')->default(0)->after("rom_tanka")->comment('電気労務単価');
            $table->integer('rom_gakuTo')->default(0)->after("rom_gaku")->comment('特殊作業員労務費の合計金額（土木など）');
            $table->integer('rom_gakuDe')->default(0)->after("rom_gaku")->comment('電気労務費の合計金額');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mitumoriKos', function (Blueprint $table) {
            $table->dropColumn(["rom_tankaTo", "rom_tankaDe",'rom_gakuTo','rom_gakuDe']);
        });
    }
};
