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
            $table->string("sName")->default('')->after('name')->comment('資材事の名前、歩掛変更時に使う');
            $table->double("tekkyo_rate")->default(1)->after('bugakariTo')->comment('撤去の場合に使う。常に歩掛に掛けるのでdefaultは1');
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
            $table->dropColumn('sName');
            $table->dropColumn('tekkyo_rate');
        });
    }
};
