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
        Schema::table('sizai_kikakus', function (Blueprint $table) {
            $table->boolean('new_sizai_m_system')->default(false)->after('in_siyo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sizai_kikakus', function (Blueprint $table) {
            $table->dropColumn('new_sizai_m_system');
        });
    }
};
