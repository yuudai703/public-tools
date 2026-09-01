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
        Schema::create('sizai_names', function (Blueprint $table) {
            $table->id();
            $table->string("code")->nullable();
            $table->integer("sub_type")->nullable();
            $table->integer("sortno")->nullable();
            $table->string("name")->nullable();
            $table->integer("seko1")->nullable();
            $table->integer("seko2")->nullable();
            $table->integer("seko3")->nullable();
            $table->integer("seko4")->nullable();
            $table->integer("seko5")->nullable();
            $table->integer("seko6")->nullable();
            $table->integer("seko7")->nullable();
            $table->integer("seko8")->nullable();
            $table->integer("seko9")->nullable();
            $table->integer("seko10")->nullable();
            $table->integer("seko11")->nullable();
            $table->integer("seko12")->nullable();
            $table->integer("seko13")->nullable();
            $table->integer("seko14")->nullable();
            $table->integer("seko15")->nullable();
            $table->integer("seko16")->nullable();
            $table->integer("seko17")->nullable();
            $table->integer("seko18")->nullable();
            $table->integer("seko19")->nullable();
            $table->integer("seko20")->nullable();
            $table->integer("seko21")->nullable();
            $table->integer("seko22")->nullable();
            $table->integer("seko23")->nullable();
            $table->integer("seko24")->nullable();
            $table->integer("seko25")->nullable();
            $table->integer("seko26")->nullable();
            $table->integer("seko27")->nullable();
            $table->integer("seko28")->nullable();
            $table->integer("seko29")->nullable();
            $table->integer("seko30")->nullable();
            $table->boolean("han")->default(false);
            $table->boolean("yotyou")->default(false);
            $table->integer("bugake_tani")->nullable();
            $table->integer("size_sort")->nullable();
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
        Schema::dropIfExists('sizai_names');
    }
};
