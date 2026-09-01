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
        Schema::create('kozi_category_names', function (Blueprint $table) {
            $table->id();
            $table->string("code1")->nullable();
            $table->string("code2")->nullable();
            $table->string("name2")->nullable();
            $table->string("tani")->nullable();
            $table->integer("kensetu")->nullable();
            $table->integer("fkensetu")->nullable();

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
        Schema::dropIfExists('kozi_category_names');
    }
};
