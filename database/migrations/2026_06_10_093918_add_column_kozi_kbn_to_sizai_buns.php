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
        Schema::table('sizai_buns', function (Blueprint $table) {
            $table->integer('kozi_kbn')->nullable(false)->after('id')->default(0)->comment('工事区分:0:電気工事,1:設備工事');
        });
    }
            
            /**
             * Reverse the migrations.
            *
            * @return void
            */
            public function down()
            {
                Schema::table('sizai_buns', function (Blueprint $table) {
                    $table->dropColumn('kozi_kbn');
        });
    }
};
