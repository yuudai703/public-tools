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
            $table->bigInteger('total_extax')->default(0)->comment('税抜合計額')->change();
            $table->bigInteger('total_tax')->default(0)->comment('税額')->change();
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
            $table->double('total_extax', 8, 2)->default(0)->comment('税抜合計額')->change();
            $table->double('total_tax', 8, 2)->default(0)->comment('税額')->change();
        });
    }
};
