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
        Schema::table('kansetu_komokus', function (Blueprint $table) {
            $table->string('explain')->nullable(false)->default('')->comment('説明');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kansetu_komokus', function (Blueprint $table) {
            $table->dropColumn('explain');
        });
    }
};
