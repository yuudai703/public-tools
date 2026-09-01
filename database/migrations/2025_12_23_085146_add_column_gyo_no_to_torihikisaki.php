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
        Schema::table('torihikisakis', function (Blueprint $table) {
            $table->integer('denkiNo')->nullable()->after('id')->comment('電気積算並び順');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('torihikisakis', function (Blueprint $table) {
            $table->dropColumn('denkiNo');
        });
    }
};
