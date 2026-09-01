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
            $table->integer('moto_tanka')->default(0)->after('tanka')->comment('相見積の元となる見積書の単価');
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
            $table->dropColumn('moto_tanka');
        });
    }
};
