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
        Schema::create('sizai_mokus', function (Blueprint $table) {
            $table->id();
            $table->string("code")->nullable();
            $table->integer("sortno")->nullable();
            $table->string("name")->nullable();
            $table->integer("use_seko")->nullable();
            $table->integer("tekkyo")->nullable();
            $table->string("yosokake")->nullable();
            $table->string("yosokans")->nullable();
            $table->string("yosoysan")->nullable();
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
        Schema::dropIfExists('sizai_mokus');
    }
};
