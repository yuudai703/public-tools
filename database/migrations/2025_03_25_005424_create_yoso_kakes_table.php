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
        Schema::create('yoso_kakes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->string('short_name')->nullable();
            $table->integer('hikaku')->nullable();
            $table->integer('t_marume')->nullable();
            $table->integer('g_marume')->nullable();
            $table->integer('t_fmarume')->nullable();
            $table->integer('g_fmarume')->nullable();
            $table->integer('b_marume')->nullable();
            $table->integer('base_tanak')->nullable();
            $table->integer('romu1')->nullable();
            $table->integer('romu2')->nullable();
            $table->integer('b_fmarume')->nullable();
            $table->integer('romu3')->nullable();
            $table->integer('romu4')->nullable();
            $table->integer('t_sumarume')->nullable();
            $table->integer('g_sumarume')->nullable();
            $table->integer('romu5')->nullable();
            $table->integer('b_sumarume')->nullable();
            $table->integer('romu6')->nullable();
            $table->integer('t_fsumarume')->nullable();
            $table->integer('g_fsumarume')->nullable();
            $table->integer('b_fsumarume')->nullable();
            $table->integer('sromu1')->nullable();
            $table->integer('sromu2')->nullable();
            $table->integer('tromu1')->nullable();
            $table->integer('sromu3')->nullable();
            $table->integer('tromu2')->nullable();
            $table->integer('sromu4')->nullable();
            $table->integer('tromu3')->nullable();
            $table->integer('sromu5')->nullable();
            $table->integer('tromu4')->nullable();
            $table->integer('sromu6')->nullable();
            $table->integer('tromu5')->nullable();
            $table->integer('tromu6')->nullable();
            $table->integer('tekkyo')->nullable();//撤去からさきのカラムは使っていない
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
        Schema::dropIfExists('yoso_kakes');
    }
};
