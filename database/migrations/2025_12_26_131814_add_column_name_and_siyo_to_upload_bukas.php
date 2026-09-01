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
        Schema::table('uploadBukas', function (Blueprint $table) {
            $table->string('siyo')->nullable()->after('kenbutu_code');
            $table->string('name')->nullable()->after('kenbutu_code');
            $table->boolean('none_partner_flg')->default(false)->after('ym');
            $table->dropColumn('updated_at');
            $table->dropColumn('created_at');
            // $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->nullable(false);
            // $table->timestamp('created_at')->useCurrent()->nullable(false);
        });
        Schema::table('uploadBukas', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->nullable(false);
            $table->timestamp('created_at')->useCurrent()->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('uploadBukas', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('siyo');
        });
    }
};
