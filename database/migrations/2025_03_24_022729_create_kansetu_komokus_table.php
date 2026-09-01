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
        Schema::create('kansetu_komokus', function (Blueprint $table) {
            $table->id();
            $table->string('bun_code')->nullable();
            $table->string('sort_no')->nullable();
            $table->string('code')->nullable();
            $table->string('parent')->nullable();
            $table->string('t_exp')->nullable();
            $table->string('g_exp')->nullable();
            $table->string('name')->nullable();
            $table->integer('type')->nullable();
            $table->integer('jouken')->nullable();
            $table->integer('t_rate')->nullable();
            $table->integer('marume')->nullable();
            $table->integer('t_marume')->nullable();
            $table->integer('t_kingaku')->nullable();
            $table->integer('t_pos')->nullable();
            $table->string('yoso_ysan')->nullable();
            $table->string('cinet_code')->nullable();
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
        Schema::dropIfExists('kansetu_komokus');
    }
};
