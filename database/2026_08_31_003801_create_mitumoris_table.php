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
            CREATE TABLE `mitumoris` (
                `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                `groupId` INT(11) NULL DEFAULT NULL,
                `code` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '相手先' COLLATE 'utf8mb4_unicode_ci',
                `title` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '工事件名' COLLATE 'utf8mb4_unicode_ci',
                `atesaki` VARCHAR(255) NOT NULL COMMENT '見積先' COLLATE 'utf8mb4_unicode_ci',
                `tantoId` INT(11) NULL DEFAULT NULL COMMENT '担当者ID',
                `area` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '施工場所' COLLATE 'utf8mb4_unicode_ci',
                `torihikiho` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '取引方法' COLLATE 'utf8mb4_unicode_ci',
                `kigen` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '有効期限' COLLATE 'utf8mb4_unicode_ci',
                `biko` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '備考' COLLATE 'utf8mb4_unicode_ci',
                `tax_rate` INT(11) NOT NULL DEFAULT '0',
                `gaku` INT(11) NOT NULL DEFAULT '0' COMMENT '金額',
                `aimitu_flg` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '相見積もりフラグ',
                `mitumoriId` INT(11) NULL DEFAULT NULL COMMENT '見積ID(相見積もりの元となる見積ID)',
                `companyId` INT(11) NULL DEFAULT NULL COMMENT '会社ID',
                `sizai_gaku` INT(11) NOT NULL DEFAULT '0' COMMENT '資材だけの合計金額 消耗品雑材も含む',
                `rom_gaku` INT(11) NOT NULL DEFAULT '0' COMMENT '労務単価の合計金額',
                `rom_gakuDe` INT(11) NOT NULL DEFAULT '0' COMMENT '電気労務費の合計金額',
                `rom_gakuTo` INT(11) NOT NULL DEFAULT '0' COMMENT '特殊作業員労務費の合計金額（土木など）',
                `print_at` DATE NULL DEFAULT NULL COMMENT '見積書印刷日',
                `kokyoF` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '公共F',
                `kokiM` INT(11) NULL DEFAULT NULL COMMENT '公共月',
                `type` VARCHAR(255) NOT NULL COMMENT 'タイプ' COLLATE 'utf8mb4_unicode_ci',
                `loginId` INT(11) NOT NULL COMMENT 'ログインID',
                `loginTime` DATETIME NOT NULL COMMENT 'ログインタイム',
                `keisyo` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '敬称' COLLATE 'utf8mb4_unicode_ci',
                `memo` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'メモ' COLLATE 'utf8mb4_unicode_ci',
                `romutan` INT(11) NOT NULL DEFAULT '0' COMMENT '労務単価',
                `syukusyaku` INT(11) NOT NULL DEFAULT '0' COMMENT '拾い縮尺',
                `deleted_at` TIMESTAMP NULL DEFAULT NULL,
                `gyoNo` INT(11) NULL DEFAULT NULL COMMENT '行ナンバー',
                `kaniFlg` TINYINT(1) NULL DEFAULT NULL COMMENT '簡易見積かどうか',
                `hukugo_tanka_flg` TINYINT(1) NOT NULL COMMENT '複合単価かどうか',
                `created_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
                `updated_at` TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                `tantoName` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '担当者名' COLLATE 'utf8mb4_unicode_ci',
                `do_at` DATETIME NULL DEFAULT NULL COMMENT '実行日時',
                `rom_tanka` INT(11) NULL DEFAULT '0' COMMENT '普通作業員',
                `rom_tankaDe` INT(11) NOT NULL DEFAULT '0' COMMENT '電気労務単価',
                `rom_tankaTo` INT(11) NOT NULL DEFAULT '0' COMMENT '特殊作業員労務単価（土木など）',
                `kansetu_auto_calculate` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '間接費、自動計算するか',
                `first_auto_changed_flg` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '初回自動計算後変更フラグ 初めての時は自動計算内容を映す',
                `kansetu_update_flg` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '1：最初の見積一覧で更新　2：更新しない',
                PRIMARY KEY (`id`) USING BTREE
            )
            COLLATE='utf8mb4_unicode_ci'
            ENGINE=InnoDB
            AUTO_INCREMENT=1270
            ;
        
        ");

    DB::statement("
        CREATE TABLE `mitumoriKos` (
        `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        `mitumoriId` BIGINT(20) UNSIGNED NOT NULL COMMENT '見積ID',
        `hiyo_kbn` INT(11) NOT NULL DEFAULT '0' COMMENT '費用区分 0:通常 1:消耗品雑材 2:経費 3:間接費',
        `gyoNo` INT(11) NULL DEFAULT NULL COMMENT '行ナンバー',
        `name` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '名' COLLATE 'utf8mb4_unicode_ci',
        `su` INT(11) NOT NULL DEFAULT '0' COMMENT '数量',
        `tani` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '単位' COLLATE 'utf8mb4_unicode_ci',
        `tanka` INT(11) NOT NULL DEFAULT '0' COMMENT '単価',
        `moto_tanka` INT(11) NOT NULL DEFAULT '0' COMMENT '相見積もりのもととなる単価',
        `auto_tanka` INT(11) NOT NULL DEFAULT '0' COMMENT '単価自動計算時の単価',
        `aimitu_tanka` INT(11) NOT NULL DEFAULT '0',
        `gaku` INT(11) NOT NULL DEFAULT '0' COMMENT '金額',
        `aimitu_gaku` INT(11) NOT NULL DEFAULT '0',
        `sizai_gaku` INT(11) NOT NULL DEFAULT '0' COMMENT '資材だけの合計金額 消耗品雑材も含む',
        `rom_gaku` INT(11) NOT NULL DEFAULT '0' COMMENT '労務単価の合計金額',
        `rom_gakuDe` INT(11) NOT NULL DEFAULT '0' COMMENT '電気労務費の合計金額',
        `rom_gakuTo` INT(11) NOT NULL DEFAULT '0' COMMENT '特殊作業員労務費の合計金額（土木など）',
        `biko` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '備考' COLLATE 'utf8mb4_unicode_ci',
        `bugakari` DOUBLE NOT NULL DEFAULT '0' COMMENT '歩掛',
        `kansetu_code` VARCHAR(255) NULL DEFAULT NULL COMMENT '何の間接費を使ったか' COLLATE 'utf8mb4_unicode_ci',
        `kansetu_bun_code` VARCHAR(255) NULL DEFAULT NULL COMMENT '何の間接費分類を使ったか' COLLATE 'utf8mb4_unicode_ci',
        `kansetu_rate` DECIMAL(5,2) NOT NULL DEFAULT '0.00' COMMENT '間接費のかけ率',
        `nomberFlg` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'ナンバーフラグ',
        `showNo` VARCHAR(255) NULL DEFAULT NULL COMMENT '表示番号' COLLATE 'utf8mb4_unicode_ci',
        `kaiLevel` INT(11) NULL DEFAULT NULL COMMENT '階層レベル',
        `kensetuhiBunrui` INT(11) NOT NULL DEFAULT '0' COMMENT '建設費分類',
        `created_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
        `updated_at` TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
        `rom_tanka` INT(11) NULL DEFAULT NULL,
        `rom_tankaDe` INT(11) NOT NULL DEFAULT '0' COMMENT '電気労務単価',
        `rom_tankaTo` INT(11) NOT NULL DEFAULT '0' COMMENT '特殊作業員労務単価（土木など）',
        `kansetu_auto_calculate` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '間接費、自動計算するか',
        `first_auto_changed_flg` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '初回自動計算後変更フラグ 初めての時は自動計算内容を映す',
        PRIMARY KEY (`id`) USING BTREE
    )
    COLLATE='utf8mb4_unicode_ci'
    ENGINE=InnoDB
    AUTO_INCREMENT=1993
    ;
    ");
    
    DB::statement("
        CREATE TABLE `mitumoriSais` (
            `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            `mitumoriId` BIGINT(20) NULL DEFAULT NULL,
            `mitumoriKoId` BIGINT(20) NULL DEFAULT NULL COMMENT '見積項目ID',
            `hiyo_kbn` INT(11) NOT NULL DEFAULT '0' COMMENT '費用区分 0:通常 1:消耗品雑材 2:経費 3:間接費',
            `gyoNo` INT(11) NULL DEFAULT '0' COMMENT '行ナンバー',
            `gyoText` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
            `zai_kbn` VARCHAR(255) NULL DEFAULT NULL COMMENT '資材区分 A:A材 B:B材 null:その他' COLLATE 'utf8mb4_unicode_ci',
            `sizaiCode` VARCHAR(255) NOT NULL DEFAULT '' COLLATE 'utf8mb4_unicode_ci',
            `name` VARCHAR(255) NULL DEFAULT '' COLLATE 'utf8mb4_unicode_ci',
            `sName` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '資材事の名前、歩掛変更時に使う' COLLATE 'utf8mb4_unicode_ci',
            `siyo` VARCHAR(255) NULL DEFAULT '' COMMENT '仕様' COLLATE 'utf8mb4_unicode_ci',
            `memo` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
            `su` DOUBLE NOT NULL DEFAULT '0' COMMENT '数量',
            `tanka` INT(11) NOT NULL DEFAULT '0' COMMENT '単価',
            `auto_tanka` INT(11) NOT NULL DEFAULT '0' COMMENT '単価自動計算時の単価',
            `moto_tanka` INT(11) NOT NULL DEFAULT '0' COMMENT '相見積の元となる見積書の単価',
            `aimitu_tanka` INT(11) NOT NULL DEFAULT '0',
            `gaku` INT(11) NOT NULL DEFAULT '0' COMMENT '金額',
            `aimitu_gaku` INT(11) NOT NULL DEFAULT '0',
            `biko` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '備考' COLLATE 'utf8mb4_unicode_ci',
            `toso` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '塗装' COLLATE 'utf8mb4_unicode_ci',
            `bugakari` DOUBLE NOT NULL DEFAULT '0' COMMENT '歩掛',
            `bugakariDe` DOUBLE NOT NULL DEFAULT '0' COMMENT '電気作業員歩掛',
            `bugakariTo` DOUBLE NOT NULL DEFAULT '0' COMMENT '特殊作業員作業員歩掛（土木など）',
            `tekkyo_rate` DOUBLE NOT NULL DEFAULT '1' COMMENT '撤去の場合に使う。常に歩掛に掛けるのでdefaultは1',
            `kansetuhiBunrui` INT(11) NOT NULL DEFAULT '0' COMMENT '建設費分類',
            `hokyuritu` DOUBLE NOT NULL DEFAULT '0' COMMENT '補給率',
            `huzokuritu1` DOUBLE NOT NULL DEFAULT '0' COMMENT '付属率1',
            `huzokuritu2` DOUBLE NOT NULL DEFAULT '0' COMMENT '付属率2',
            `huzokuritu3` DOUBLE NOT NULL DEFAULT '0' COMMENT '付属率3',
            `zatuzairitu` DOUBLE NOT NULL DEFAULT '0' COMMENT '雑材率',
            `sonotaritu` DOUBLE NOT NULL DEFAULT '0' COMMENT 'その他率',
            `size1` DOUBLE NOT NULL DEFAULT '0',
            `size2` DOUBLE NOT NULL DEFAULT '0',
            `size3` DOUBLE NOT NULL DEFAULT '0',
            `chengeCost` INT(11) NOT NULL DEFAULT '0' COMMENT '取替費',
            `disposalCost` INT(11) NOT NULL DEFAULT '0' COMMENT '処分費',
            `tani` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '単位' COLLATE 'utf8mb4_unicode_ci',
            `seko` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '施工方法' COLLATE 'utf8mb4_unicode_ci',
            `kansetu_code` VARCHAR(255) NULL DEFAULT NULL COMMENT '何の間接費を使ったか' COLLATE 'utf8mb4_unicode_ci',
            `kansetu_bun_code` VARCHAR(255) NULL DEFAULT NULL COMMENT '何の間接費分類を使ったか' COLLATE 'utf8mb4_unicode_ci',
            `kansetu_rate` DECIMAL(5,2) NOT NULL DEFAULT '0.00' COMMENT '間接費のかけ率',
            `sizaiId` INT(11) NULL DEFAULT NULL COMMENT '選択した資材ID',
            `seko_kbn` INT(11) NOT NULL DEFAULT '0' COMMENT '0:行追加,1:新設,2:撤去(再利用),3:撤去,4:再取付',
            `sagyoin_kbn` INT(11) NOT NULL DEFAULT '0' COMMENT '作業員区分 1:電気工事作業員 2:普通作業員 3:特殊作業員',
            `tanka_rendo_code` VARCHAR(255) NULL DEFAULT '' COMMENT '単価連動コード id+施工区分' COLLATE 'utf8mb4_unicode_ci',
            `bugakari_rendo_code` VARCHAR(255) NULL DEFAULT '' COMMENT '歩掛連動コード id+施工区分+施工コード+作業員区分＋' COLLATE 'utf8mb4_unicode_ci',
            `seko_code` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'コンクリート打ち込みなど' COLLATE 'utf8mb4_unicode_ci',
            `created_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
            `updated_at` TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
            PRIMARY KEY (`id`) USING BTREE
        )
        COLLATE='utf8mb4_unicode_ci'
        ENGINE=InnoDB
        AUTO_INCREMENT=8073
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
        Schema::dropIfExists('mitumoris');
        Schema::dropIfExists('mitumoriKos');
        Schema::dropIfExists('mitumoriSais');
    }
};
