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
        Schema::create('rom_tankas', function (Blueprint $table) {
            $table->id();
            
            $table->string("code")->nullable();
            $table->string("sortNo")->nullable();
            $table->string("name")->nullable();
            $table->string("keijo_name")->nullable();
            $table->string("short_name")->nullable();
            $table->integer("t_tanka1")->nullable();
            $table->integer("t_tanka2")->nullable();
            $table->integer("t_tanka3")->nullable();
            $table->integer("t_tanka4")->nullable();
            $table->integer("t_tanka5")->nullable();
            
            $table->integer("g_tanka1")->nullable();
            $table->integer("g_tanka2")->nullable();
            $table->integer("g_tanka3")->nullable();
            $table->integer("g_tanka4")->nullable();
            $table->integer("g_tanka5")->nullable();
            $table->string("yoso_kake")->nullable();
            $table->string("yoso_kans")->nullable();
            $table->string("yoso_ysan")->nullable();
            $table->string("cinet_code")->nullable();
            
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
        Schema::dropIfExists('rom_tankas');
    }
};
