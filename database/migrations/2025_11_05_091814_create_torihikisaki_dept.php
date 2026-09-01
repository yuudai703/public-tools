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
        Schema::create('torihikisaki_dept', function (Blueprint $table) {
            $table->id();
            $table->integer('torihikisaki_id')->default(0)->comment('取引先（見積）テーブルID');
            $table->integer('dept_id')->default(0)->comment('部署テーブルID');
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
        Schema::dropIfExists('torihikisaki_dept');
    }
};
