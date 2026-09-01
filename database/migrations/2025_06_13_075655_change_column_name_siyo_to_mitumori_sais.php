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
            $table->string('name')->nullable(true)->change();
            $table->string('siyo')->nullable(true)->change();
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
            $table->string('name')->default("")->change();
            $table->string("siyo")->default("")->change();
        });
    }
};
