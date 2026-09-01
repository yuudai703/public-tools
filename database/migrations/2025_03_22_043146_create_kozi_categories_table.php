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
        Schema::create('kozi_categories', function (Blueprint $table) {
            $table->id();
            $table->string("code1")->nullable();
            $table->string("name1")->nullable();
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
        Schema::dropIfExists('kozi_categories');
    }
};
