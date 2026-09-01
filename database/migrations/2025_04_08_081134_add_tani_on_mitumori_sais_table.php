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
            $table->string("tani")->default("")->comment("単位");
            $table->string("seko")->default("")->comment("施工方法");
            $table->renameColumn('hinCode', 'sizaiCode');
            $table->renameColumn('hinName', 'name');
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
            $table->dropColumn("tani");
            $table->dropColumn("seko");
            $table->renameColumn('sizaiCode', 'hinCode');
            $table->renameColumn('name', 'hinName');
        });
    }
};
