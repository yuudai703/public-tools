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
            $table->string('zai_kbn')->nullable()->after('gyoNo')
            ->comment('資材区分 A:A材 B:B材 null:その他');
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
            $table->dropColumn('zai_kbn');
        });
    }
};
