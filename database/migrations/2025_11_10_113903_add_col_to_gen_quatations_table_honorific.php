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
        Schema::table('gen_quatations', function (Blueprint $table) {
            //
            $table->string('honorific')->default('御中')->comment('敬称');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gen_quatations', function (Blueprint $table) {
            //
            $table->dropColumn('honorific');
        });
    }
};
