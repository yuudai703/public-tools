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
    {//一つで良さそうなのでname カラムで統一
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['name1','name2']);
            $table->string('name')->nullable(false)->default("")->after("id")->comment('会社名');
        });
    }

    /**
     * Reverse the migrations.光電気工事株式会社
* 有限会社森電気商会
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('name1')->nullable(false)->default("")->comment('会社名1');
            $table->string('name2')->nullable(false)->default("")->comment('会社名2');
            $table->dropColumn('name');
        });
    }
};
