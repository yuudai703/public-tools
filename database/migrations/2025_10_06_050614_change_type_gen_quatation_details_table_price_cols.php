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
        Schema::table('gen_quatation_details', function (Blueprint $table) {
            //
            $table->bigInteger('unit_price')->default(0)->comment('単価')->change();
            $table->bigInteger('price')->default(0)->comment('金額')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gen_quatation_details', function (Blueprint $table) {
            //
            $table->double('unit_price', 8, 2)->nullable()->comment('単価')->change();
            $table->double('price', 8, 2)->nullable()->comment('金額')->change();
        });
    }
};
