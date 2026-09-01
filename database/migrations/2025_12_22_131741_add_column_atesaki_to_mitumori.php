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
        Schema::table('mitumoris', function (Blueprint $table) {
            $table->string('atesaki')->nullable(false)->after('title')->comment('見積先');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mitumoris', function (Blueprint $table) {
            $table->dropColumn('atesaki');
        });
    }
};
