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
            $table->integer('auto_tanka')->default(0)->after('tanka')->comment('単価自動計算時の単価');
        });
        Schema::table('mitumoriKos', function (Blueprint $table) {
            $table->integer('auto_tanka')->default(0)->after('tanka')->comment('単価自動計算時の単価');
            $table->integer('moto_tanka')->default(0)->after('tanka')->comment('相見積もりのもととなる単価');
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
            $table->dropColumn('auto_tanka');
        });
        Schema::table('mitumoriKos', function (Blueprint $table) {
            $table->dropColumn('auto_tanka');
            $table->dropColumn('moto_tanka');
        });
    }
};
