<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE TABLE mitumoriKoRekis like mitumoriKos");
        DB::statement("
            ALTER TABLE mitumoriKoRekis
            DROP PRIMARY KEY,
            MODIFY id BIGINT UNSIGNED NOT NULL,
            ADD reki_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY FIRST,
            ADD reki_url VARCHAR(255) NOT NULL AFTER reki_id,
            ADD past_or_future VARCHAR(255) NOT NULL AFTER reki_id,
            ADD reki_no INT UNSIGNED NOT NULL AFTER reki_url,
            MODIFY created_at DATETIME,
            MODIFY updated_at DATETIME
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mitumoriKoRekis');
    }
};
