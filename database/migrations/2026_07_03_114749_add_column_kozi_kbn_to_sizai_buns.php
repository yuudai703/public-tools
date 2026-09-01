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



            $table->boolean('denki_flg')->after('kozi_kbn')->default(false)->comment('電気区分');
            $table->boolean('setubi_flg')->after('denki_flg')->default(false)->comment('設備区分');
            $table->dropColumn('kozi_kbn');
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
                $table->string('kozi_kbn')->nullable()->default('')->comment('構造区分');
                $table->dropColumn('denki_flg');
                $table->dropColumn('setubi_flg');
            });
        }
};
