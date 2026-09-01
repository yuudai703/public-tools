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
            $table->boolean('kansetu_flg')->default(false)->comment('間接費かどうか');
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
            $table->dropColumn('kansetu_flg');            
        });
    }
};
