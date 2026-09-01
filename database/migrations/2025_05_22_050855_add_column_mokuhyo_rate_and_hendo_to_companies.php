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
        Schema::table('companies', function (Blueprint $table) {
            $table->decimal('mokuhyo_rate', 3, 2)->default(1.00)->comment('目標率');
            $table->decimal('hendo_rate', 3, 2)->default(0.00)->comment('変動幅');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('mokuhyo_rate');
            $table->dropColumn('hendo_rate');
        });
    }
};
