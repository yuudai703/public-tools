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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name1')->nullable(false)->default("")->comment('会社名1');
            $table->string('name2')->nullable(false)->default("")->comment('会社名2');
            $table->string('address')->nullable(false)->default("")->comment('住所');
            $table->string('phone')->nullable(false)->default("")->comment('電話番号');
            $table->string('delegate')->nullable(false)->default("")->comment('代表名前');
            $table->string('post')->nullable(false)->default("")->comment('代表役所');
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
        Schema::dropIfExists('companies');
    }
};
