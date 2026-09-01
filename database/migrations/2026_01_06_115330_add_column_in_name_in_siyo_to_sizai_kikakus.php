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
            $table->date('dvd_ymd')->nullable()->after('made_in_me');
            $table->string('in_siyo')->default("")->nullable()->after('made_in_me');
            $table->string('in_name')->default("")->nullable()->after('made_in_me');
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
            $table->dropColumn(['in_siyo', 'in_name','dvd_ymd']);
        });
    }
};
