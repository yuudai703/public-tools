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
       DB::statement("
            CREATE TABLE `kansetu_calculations` (
            `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            `mitumoriId` INT(11) NOT NULL,
            `kansetu_bun_code` VARCHAR(255) NULL DEFAULT NULL COMMENT '何の間接費分類を使ったか' COLLATE 'utf8mb4_unicode_ci',
            `kansetu_code` VARCHAR(255) NULL DEFAULT NULL COMMENT '何の間接費を使ったか' COLLATE 'utf8mb4_unicode_ci',
            `name` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '間接費名称' COLLATE 'utf8mb4_unicode_ci',
            `kansetu_rate` DECIMAL(5,2) NOT NULL DEFAULT '0.00' COMMENT '間接費のかけ率',
            `created_at` TIMESTAMP NULL DEFAULT NULL,
            `updated_at` TIMESTAMP NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE
        )
        COLLATE='utf8mb4_unicode_ci'
        ENGINE=InnoDB
        AUTO_INCREMENT=2784
        ;
       ");

       DB::statement("
            CREATE TABLE `kansetu_buns` (
            `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            `code` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
            `name` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
            `use` INT(11) NOT NULL DEFAULT '0',
            `updated_at` TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
            `created_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY (`id`) USING BTREE
        )
        COLLATE='utf8mb4_unicode_ci'
        ENGINE=InnoDB
        AUTO_INCREMENT=9
        ;
       ");

       DB::statement("
            CREATE TABLE `kansetu_komokus` (
                `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                `bun_code` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                `sort_no` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                `code` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                `parent` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                `t_exp` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                `g_exp` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                `name` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                `type` INT(11) NULL DEFAULT NULL,
                `jouken` INT(11) NULL DEFAULT NULL,
                `t_rate` INT(11) NULL DEFAULT NULL,
                `marume` INT(11) NULL DEFAULT NULL,
                `t_marume` INT(11) NULL DEFAULT NULL,
                `t_kingaku` INT(11) NULL DEFAULT NULL,
                `t_pos` INT(11) NULL DEFAULT NULL,
                `yoso_ysan` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                `cinet_code` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                `j_exp` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '計算方法（率以外）日本語' COLLATE 'utf8mb4_unicode_ci',
                `rate_name` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'かけ率の名称' COLLATE 'utf8mb4_unicode_ci',
                `rate` DECIMAL(5,2) NOT NULL DEFAULT '0.00' COMMENT '間接費それぞれのかけ率',
                `updated_at` TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                `created_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
                `explain` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '説明' COLLATE 'utf8mb4_unicode_ci',
                PRIMARY KEY (`id`) USING BTREE
            )
            COLLATE='utf8mb4_unicode_ci'
            ENGINE=InnoDB
            AUTO_INCREMENT=95
            ;
       ");

       DB::statement("
        CREATE TABLE `tekkyo_rates` (
            `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            `code` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
            `name` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
            `ari` DOUBLE NOT NULL DEFAULT '0',
            `nasi` DOUBLE NOT NULL DEFAULT '0',
            `updated_at` TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
            `created_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY (`id`) USING BTREE
        )
        COLLATE='utf8mb4_unicode_ci'
        ENGINE=InnoDB
        AUTO_INCREMENT=14
        ;
       
       ");
       DB::statement("
                CREATE TABLE `keep_sizais` (
                    `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                    `sekoCode` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
                    `bugakariColumn` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
                    `sizaiCode` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
                    `mitumoriId` BIGINT(20) NULL DEFAULT NULL,
                    `mitumoriKoId` BIGINT(20) NULL DEFAULT NULL,
                    `kikakuId` BIGINT(20) NULL DEFAULT NULL,
                    `su` INT(11) NOT NULL,
                    `created_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
                    `updated_at` TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                    PRIMARY KEY (`id`) USING BTREE
                )
                COMMENT='資材追加ウィンドで資材数を変更したときに一時的に保存するテーブル'
                COLLATE='utf8mb4_unicode_ci'
                ENGINE=InnoDB
                AUTO_INCREMENT=1184
                ;
       ");
       DB::statement("
                CREATE TABLE `keihis` (
                `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '経費名' COLLATE 'utf8mb4_unicode_ci',
                `siyo` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '仕様' COLLATE 'utf8mb4_unicode_ci',
                `tani` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '単位' COLLATE 'utf8mb4_unicode_ci',
                `tanka` INT(11) NOT NULL DEFAULT '0' COMMENT '単価',
                `created_at` TIMESTAMP NULL DEFAULT NULL,
                `updated_at` TIMESTAMP NULL DEFAULT NULL,
                PRIMARY KEY (`id`) USING BTREE
            )
            COLLATE='utf8mb4_unicode_ci'
            ENGINE=InnoDB
            AUTO_INCREMENT=3
            ;
       ");
       DB::statement("
            CREATE TABLE `companies` (
                `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '会社名' COLLATE 'utf8mb4_unicode_ci',
                `address` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '住所' COLLATE 'utf8mb4_unicode_ci',
                `phone` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '電話番号' COLLATE 'utf8mb4_unicode_ci',
                `delegate` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '代表名前' COLLATE 'utf8mb4_unicode_ci',
                `post` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '代表役所' COLLATE 'utf8mb4_unicode_ci',
                `my_flg` TINYINT(1) NOT NULL COMMENT '自社フラグ',
                `updated_at` TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                `created_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
                `fax` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'FAX番号' COLLATE 'utf8mb4_unicode_ci',
                `yubin_no` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '郵便番号' COLLATE 'utf8mb4_unicode_ci',
                `mokuhyo_rate` DECIMAL(3,2) NOT NULL DEFAULT '1.00' COMMENT '目標率',
                `hendo_rate` DECIMAL(3,2) NOT NULL DEFAULT '0.00' COMMENT '変動幅',
                PRIMARY KEY (`id`) USING BTREE
            )
            COLLATE='utf8mb4_unicode_ci'
            ENGINE=InnoDB
            AUTO_INCREMENT=5
            ;
       ");
       DB::statement("
           CREATE TABLE `logs` (
                `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                `user_id` BIGINT(20) NOT NULL,
                `mitumori_id` BIGINT(20) NULL DEFAULT NULL,
                `mitumoriKo_id` BIGINT(20) NULL DEFAULT NULL,
                `mitumoriSai_id` BIGINT(20) NULL DEFAULT NULL,
                `action` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                `created_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
                `updated_at` TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                PRIMARY KEY (`id`) USING BTREE
            )
            COLLATE='utf8mb4_unicode_ci'
            ENGINE=InnoDB
            AUTO_INCREMENT=4965
            ;
       ");
       DB::statement("
           CREATE TABLE `sekos` (
                `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                `code` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                `name` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                `bugaka_code` INT(11) NOT NULL DEFAULT '0',
                `doko` INT(11) NOT NULL DEFAULT '0',
                `back_color` INT(11) NOT NULL DEFAULT '0',
                `org_seko` INT(11) NOT NULL DEFAULT '0',
                `fore_color` INT(11) NOT NULL DEFAULT '0',
                `updated_at` TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                `created_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
                PRIMARY KEY (`id`) USING BTREE
            )
            COLLATE='utf8mb4_unicode_ci'
            ENGINE=InnoDB
            AUTO_INCREMENT=53
            ;
       ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kansetu_calculations');
        Schema::dropIfExists('kansetu_buns');
        Schema::dropIfExists('kansetu_komokus');
        Schema::dropIfExists('tekkyo_rates');
        Schema::dropIfExists('keep_sizais');
        Schema::dropIfExists('keihis');
        Schema::dropIfExists('companies');
        Schema::dropIfExists('logs');
        Schema::dropIfExists('sekos');
    }
};
