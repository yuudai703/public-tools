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
        Schema::create('keihis', function (Blueprint $table) {
            $table->id();
            $table->string("name")->default("")->comment("経費名");
            $table->string("siyo")->default("")->comment("仕様");
            $table->string("tani")->default("")->comment("単位");
            $table->integer("tanka")->default(0)->comment("単価");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('keihis');
    }
};
