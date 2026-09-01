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
            CREATE TABLE `sizai_buns` (
                `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                `denki_flg` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '電気区分',
                `setubi_flg` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '設備区分',
                `code` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                `type` INT(11) NULL DEFAULT NULL,
                `name` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                `made_in_me` TINYINT(1) NOT NULL DEFAULT '0',
                `updated_at` TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                `created_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
                `deleted_at` TIMESTAMP NULL DEFAULT NULL,
                PRIMARY KEY (`id`) USING BTREE
            )
            COLLATE='utf8mb4_unicode_ci'
            ENGINE=InnoDB
            AUTO_INCREMENT=10
            ;
        ");

        DB::statement("
            CREATE TABLE `sizai_mokus` (
                `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                `code` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                `sortno` INT(11) NULL DEFAULT NULL,
                `name` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                `use_seko` INT(11) NULL DEFAULT NULL,
                `tekkyo` INT(11) NULL DEFAULT NULL,
                `made_in_me` TINYINT(1) NOT NULL DEFAULT '0',
                `yosokake` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                `yosokans` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                `yosoysan` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                `updated_at` TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                `created_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
                `deleted_at` TIMESTAMP NULL DEFAULT NULL,
                PRIMARY KEY (`id`) USING BTREE
            )
            COLLATE='utf8mb4_unicode_ci'
            ENGINE=InnoDB
            AUTO_INCREMENT=179
            ;
        
        ");

        DB::statement("
            CREATE TABLE `sizai_names` (
                `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                `code` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                `sub_type` INT(11) NULL DEFAULT NULL,
                `sortno` INT(11) NULL DEFAULT NULL,
                `name` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                `seko1` INT(11) NULL DEFAULT NULL,
                `seko2` INT(11) NULL DEFAULT NULL,
                `seko3` INT(11) NULL DEFAULT NULL,
                `seko4` INT(11) NULL DEFAULT NULL,
                `seko5` INT(11) NULL DEFAULT NULL,
                `seko6` INT(11) NULL DEFAULT NULL,
                `seko7` INT(11) NULL DEFAULT NULL,
                `seko8` INT(11) NULL DEFAULT NULL,
                `seko9` INT(11) NULL DEFAULT NULL,
                `seko10` INT(11) NULL DEFAULT NULL,
                `seko11` INT(11) NULL DEFAULT NULL,
                `seko12` INT(11) NULL DEFAULT NULL,
                `seko13` INT(11) NULL DEFAULT NULL,
                `seko14` INT(11) NULL DEFAULT NULL,
                `seko15` INT(11) NULL DEFAULT NULL,
                `seko16` INT(11) NULL DEFAULT NULL,
                `seko17` INT(11) NULL DEFAULT NULL,
                `seko18` INT(11) NULL DEFAULT NULL,
                `seko19` INT(11) NULL DEFAULT NULL,
                `seko20` INT(11) NULL DEFAULT NULL,
                `seko21` INT(11) NULL DEFAULT NULL,
                `seko22` INT(11) NULL DEFAULT NULL,
                `seko23` INT(11) NULL DEFAULT NULL,
                `seko24` INT(11) NULL DEFAULT NULL,
                `seko25` INT(11) NULL DEFAULT NULL,
                `seko26` INT(11) NULL DEFAULT NULL,
                `seko27` INT(11) NULL DEFAULT NULL,
                `seko28` INT(11) NULL DEFAULT NULL,
                `seko29` INT(11) NULL DEFAULT NULL,
                `seko30` INT(11) NULL DEFAULT NULL,
                `han` TINYINT(1) NOT NULL DEFAULT '0',
                `yotyou` TINYINT(1) NOT NULL DEFAULT '0',
                `bugake_tani` INT(11) NULL DEFAULT NULL,
                `size_sort` INT(11) NULL DEFAULT NULL,
                `yosokake` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                `yosokans` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                `yosoysan` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                `made_in_me` TINYINT(1) NOT NULL DEFAULT '0',
                `updated_at` TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                `created_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
                PRIMARY KEY (`id`) USING BTREE
            )
            COLLATE='utf8mb4_unicode_ci'
            ENGINE=InnoDB
            AUTO_INCREMENT=3362
            ;
        
        ");
        DB::statement("
            CREATE TABLE `sizai_kikakus` (
                `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                `gyoNo` INT(11) NULL DEFAULT NULL,
                `mitumoriId` BIGINT(20) NULL DEFAULT NULL COMMENT '対象の見積ID',
                `code` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                `size1` INT(11) NULL DEFAULT NULL,
                `size2` INT(11) NULL DEFAULT NULL,
                `size3` INT(11) NULL DEFAULT NULL,
                `name` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                `name2` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                `tani` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                `bugakaA01` DOUBLE NULL DEFAULT '0',
                `bugakaA02` DOUBLE NULL DEFAULT '0',
                `bugakaA03` DOUBLE NULL DEFAULT '0',
                `bugakaA04` DOUBLE NULL DEFAULT '0',
                `bugakaA05` DOUBLE NULL DEFAULT '0',
                `bugakaA06` DOUBLE NULL DEFAULT '0',
                `bugakaA07` DOUBLE NULL DEFAULT '0',
                `bugakaA08` DOUBLE NULL DEFAULT '0',
                `bugakaA09` DOUBLE NULL DEFAULT '0',
                `bugakaA10` DOUBLE NULL DEFAULT '0',
                `bugakaA11` DOUBLE NULL DEFAULT '0',
                `bugakaA12` DOUBLE NULL DEFAULT '0',
                `bugakaA13` DOUBLE NULL DEFAULT '0',
                `bugakaA14` DOUBLE NULL DEFAULT '0',
                `bugakaA15` DOUBLE NULL DEFAULT '0',
                `bugakaB01` DOUBLE NULL DEFAULT '0',
                `bugakaB02` DOUBLE NULL DEFAULT '0',
                `bugakaB03` DOUBLE NULL DEFAULT '0',
                `bugakaB04` DOUBLE NULL DEFAULT '0',
                `bugakaB05` DOUBLE NULL DEFAULT '0',
                `bugakaB06` DOUBLE NULL DEFAULT '0',
                `bugakaB07` DOUBLE NULL DEFAULT '0',
                `bugakaB08` DOUBLE NULL DEFAULT '0',
                `bugakaB09` DOUBLE NULL DEFAULT '0',
                `bugakaB10` DOUBLE NULL DEFAULT '0',
                `bugakaB11` DOUBLE NULL DEFAULT '0',
                `bugakaB12` DOUBLE NULL DEFAULT '0',
                `bugakaB13` DOUBLE NULL DEFAULT '0',
                `bugakaB14` DOUBLE NULL DEFAULT '0',
                `bugakaB15` DOUBLE NULL DEFAULT '0',
                `tanka1` DOUBLE NULL DEFAULT '0',
                `tanka2` DOUBLE NULL DEFAULT '0',
                `tanka3` DOUBLE NULL DEFAULT '0',
                `tanka4` DOUBLE NULL DEFAULT '0',
                `tanka5` DOUBLE NULL DEFAULT '0',
                `tanil` DOUBLE NULL DEFAULT NULL,
                `tankg` DOUBLE NULL DEFAULT NULL,
                `recmemo1` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                `recmemo2` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                `recmemo3` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                `recmemo4` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                `recmemo5` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                `yosokake` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                `recmemoA` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                `yosokans` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                `recmemoB` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                `yosoyosan` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                `kenbutu_code` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                `sekisi_code` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                `sinet_code` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                `made_in_me` TINYINT(1) NOT NULL DEFAULT '0',
                `in_name` VARCHAR(255) NULL DEFAULT '' COLLATE 'utf8mb4_unicode_ci',
                `in_siyo` VARCHAR(255) NULL DEFAULT '' COLLATE 'utf8mb4_unicode_ci',
                `new_sizai_m_system` TINYINT(1) NOT NULL DEFAULT '0',
                `dvd_ymd` DATE NULL DEFAULT NULL,
                `created_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
                `updated_at` TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                PRIMARY KEY (`id`) USING BTREE,
                INDEX `sizai_kikakus_kenbutu_code_index` (`kenbutu_code`) USING BTREE
            )
            COLLATE='utf8mb4_unicode_ci'
            ENGINE=InnoDB
            AUTO_INCREMENT=40371
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
        Schema::dropIfExists('sizai_buns');
        Schema::dropIfExists('sizai_mokus');
        Schema::dropIfExists('sizai_names');
        Schema::dropIfExists('sizai_kikakus');
    }
};
