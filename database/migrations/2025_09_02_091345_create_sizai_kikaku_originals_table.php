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
        Schema::create('sizai_kikaku_originals', function (Blueprint $table) {
            $table->id();
            $table->string("code")->nullable();
            $table->integer("size1")->nullable();
            $table->integer("size2")->nullable();
            $table->integer("size3")->nullable();
            $table->string("name")->nullable();
            $table->string("name2")->nullable();
            $table->string("tani")->nullable();
            $table->double("bugakaA01")->nullable();
            $table->double("bugakaA02")->nullable();
            $table->double("bugakaA03")->nullable();
            $table->double("bugakaA04")->nullable();
            $table->double("bugakaA05")->nullable();
            $table->double("bugakaA06")->nullable();
            $table->double("bugakaA07")->nullable();
            $table->double("bugakaA08")->nullable();
            $table->double("bugakaA09")->nullable();
            $table->double("bugakaA10")->nullable();
            $table->double("bugakaA11")->nullable();
            $table->double("bugakaA12")->nullable();
            $table->double("bugakaA13")->nullable();
            $table->double("bugakaA14")->nullable();
            $table->double("bugakaA15")->nullable();
            $table->double("bugakaB01")->nullable();
            $table->double("bugakaB02")->nullable();
            $table->double("bugakaB03")->nullable();
            $table->double("bugakaB04")->nullable();
            $table->double("bugakaB05")->nullable();
            $table->double("bugakaB06")->nullable();
            $table->double("bugakaB07")->nullable();
            $table->double("bugakaB08")->nullable();
            $table->double("bugakaB09")->nullable();
            $table->double("bugakaB10")->nullable();
            $table->double("bugakaB11")->nullable();
            $table->double("bugakaB12")->nullable();
            $table->double("bugakaB13")->nullable();
            $table->double("bugakaB14")->nullable();
            $table->double("bugakaB15")->nullable();
            $table->double("tanka1")->nullable();
            $table->double("tanka2")->nullable();
            $table->double("tanka3")->nullable();
            $table->double("tanka4")->nullable();
            $table->double("tanka5")->nullable();
            $table->double("tanil")->nullable();
            $table->double("tankg")->nullable();
            $table->string("recmemo1")->nullable();
            $table->string("recmemo2")->nullable();
            $table->string("recmemo3")->nullable();
            $table->string("recmemo4")->nullable();
            $table->string("recmemo5")->nullable();
            $table->string("yosokake")->nullable();
            $table->string("recmemoA")->nullable();
            $table->string("yosokans")->nullable();
            $table->string("recmemoB")->nullable();
            $table->string("yosoyosan")->nullable();
            $table->string("kenbutu_code")->nullable();
            $table->string("sekisi_code")->nullable();
            $table->string("sinet_code")->nullable();
            $table->timestamp('created_at')->useCurrent()->nullable(false);
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sizai_kikaku_originals');
    }
};
