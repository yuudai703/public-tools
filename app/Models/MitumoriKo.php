<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MitumoriKo extends Model
{
    protected $table = "mitumoriKos";
    use HasFactory;

    public function scopeWithGaku($query, $mitumoriId)
    {
        $mitumori = DB::table("mitumoris")->where("id", $mitumoriId)->first();
        $kansetu_auto_calculate = $mitumori->kansetu_auto_calculate;
        $mitumoriKos = DB::table("mitumoriKos")->where("mitumoriId", $mitumoriId)
        ->select('id','rom_tankaDe','rom_tanka','rom_tankaTo','kansetu_auto_calculate','rom_gaku');
        
        if($mitumori->aimitu_flg == 0){
            $mitumoriSais = DB::table("mitumoriSais as mS")
            ->where("mS.mitumoriId", $mitumoriId)
            ->joinSub($mitumoriKos,"mK",function($join){
                $join->on("mK.id",'mS.mitumoriKoId');
            })
            ->select(
                "mitumoriKoId",
                DB::raw("
                    if('$mitumori->hukugo_tanka_flg'=0,
                        sum(
                            round(
                                cast(/* 四捨五入する前に固定小数点型にする*/
                                    mS.su*if(mK.kansetu_auto_calculate = 1 and mS.hiyo_kbn=1,mS.auto_tanka,mS.tanka)
                                as decimal(10,2)))
                        )+mK.rom_gaku,
                        sum(
                            round(
                                cast(((/*複合単価*/
                                    round(mS.bugakariDe*mS.tekkyo_rate*mK.rom_tankaDe)+
                                    round(mS.bugakari*mS.tekkyo_rate*mK.rom_tanka)+
                                    round(mS.bugakariTo*mS.tekkyo_rate*mK.rom_tankaTo)+
                                    if(mK.kansetu_auto_calculate = 1 and mS.hiyo_kbn=1,mS.auto_tanka,mS.tanka)
                                )*mS.su)as decimal(10,2))
                            )
                        )
                    )
                    as gaku"),
                DB::raw("
                    if('$mitumori->hukugo_tanka_flg'=0,
                        sum(mS.su*mS.bugakariDe*mS.tekkyo_rate),
                        sum(0)
                    )
                     as bugakariDe"),
                DB::raw("
                    if('$mitumori->hukugo_tanka_flg'=0,
                        sum(mS.su*mS.bugakari*mS.tekkyo_rate),
                        sum(0)
                    )
                     as bugakari"),
                DB::raw("
                    if('$mitumori->hukugo_tanka_flg'=0,
                        sum(mS.su*mS.bugakariTo*mS.tekkyo_rate),
                        sum(0)
                    )
                     as bugakariTo"),
                DB::raw("
                    if('$mitumori->hukugo_tanka_flg'=0,
                        sum(
                            round(
                                cast(
                                    (mS.su*
                                    case
                                        when mS.hiyo_kbn=0 then mS.tanka /*普通の資材*/
                                        when mS.hiyo_kbn=1 and mK.kansetu_auto_calculate = 0 then mS.tanka /*消耗品雑材*/
                                        when mS.hiyo_kbn=1 and mK.kansetu_auto_calculate = 1 then mS.auto_tanka  /*消耗品雑材 自動計算*/
                                        else 0 
                                    end)
                                as decimal(10,2))
                            )
                        )
                        ,0
                    )
                    as sizai"),
                    
            )
            ->groupBy("mitumoriKoId");

        return $query
            ->where("mitumoriKos.mitumoriId", $mitumoriId)
            ->leftJoinSub($mitumoriSais, "mitumoriSais", function ($join) {
                $join->on("mitumoriKos.id", "mitumoriSais.mitumoriKoId");
            })
            ->select(
                "mitumoriKos.id",
                "mitumoriKos.gyoNo",
                "mitumoriKos.name",
                "mitumoriKos.hiyo_kbn",
                "mitumoriKos.biko",
                "mitumoriKos.tani",
                "mitumoriKos.su",
                "mitumoriKos.kansetu_code",
                "mitumoriKos.rom_tankaDe",
                "mitumoriKos.rom_tanka",
                "mitumoriKos.rom_tankaTo",
                "mitumoriSais.sizai",
                DB::raw("
                    /*03/24 労務額対象column変更
                    round(mitumoriSais.bugakariDe * rom_tankaDe)
                    +round(mitumoriSais.bugakari * rom_tanka)
                    +round(mitumoriSais.bugakariTo * rom_tankaTo)*/
                    mitumoriKos.rom_gaku as rom_gaku
                "),
                

                DB::raw("
                    (case
                        when hiyo_kbn = 0 then
                            mitumoriSais.gaku
                            /*+ round(mitumoriSais.bugakariDe * rom_tankaDe)
                            + round(mitumoriSais.bugakari * rom_tanka)
                            + round(mitumoriSais.bugakariTo * rom_tankaTo)*/
                        when hiyo_kbn in (2,4,5) then
                            mitumoriKos.tanka
                        when hiyo_kbn = 3 then 
                            if('$kansetu_auto_calculate'=1,mitumoriKos.auto_tanka,mitumoriKos.tanka) 
                    end)
                    as tanka
                "),
                DB::raw("
                    
                        (case
                            when hiyo_kbn = 0 then
                                mitumoriSais.gaku
                                /*+ round(mitumoriSais.bugakariDe * rom_tankaDe)
                                + round(mitumoriSais.bugakari * rom_tanka)
                                + round(mitumoriSais.bugakariTo * rom_tankaTo)*/
                            when hiyo_kbn in (2,4,5) then
                                mitumoriKos.tanka
                            when hiyo_kbn = 3 then 
                                if('$kansetu_auto_calculate'=1,mitumoriKos.auto_tanka,mitumoriKos.tanka) 
                            end)*su
                     as gaku
                ")
            );
            // ->orderBy("mitumoriKos.hiyo_kbn")
            // ->orderBy("mitumoriKos.gyoNo");

        }else{
            $mitumoriSais = DB::table("mitumoriSais as mS")
            ->where("mS.mitumoriId", $mitumoriId)
            ->select(
                "mitumoriKoId",
                
                DB::raw("sum(
                        round(cast(
                            mS.su*mS.tanka
                        as decimal(10,2)))
                    ) as gaku"),

                DB::raw("sum(
                        round(cast(
                            mS.su*mS.moto_tanka
                        as decimal(10,2)))
                    ) as moto_gaku"),//相見積もりの時のもととなる見積の金額
                
                DB::raw("
                    if('$mitumori->hukugo_tanka_flg'=0,
                        sum(mS.su*mS.bugakariDe*mS.tekkyo_rate),
                        sum(0)
                    )
                     as bugakariDe"),
                DB::raw("
                    if('$mitumori->hukugo_tanka_flg'=0,
                        sum(mS.su*mS.bugakari*mS.tekkyo_rate),
                        sum(0)
                    )
                     as bugakari"),
                DB::raw("
                    if('$mitumori->hukugo_tanka_flg'=0,
                        sum(mS.su*mS.bugakariTo*mS.tekkyo_rate),
                        sum(0)
                    )
                     as bugakariTo")
            )
            ->groupBy("mitumoriKoId");

        


        return $query
            ->where("mitumoriKos.mitumoriId", $mitumoriId)
            ->leftJoinSub($mitumoriSais, "mitumoriSais", function ($join) {
                $join->on("mitumoriKos.id", "mitumoriSais.mitumoriKoId");
            })
            
            ->select(
                "mitumoriKos.id",
                "mitumoriKos.gyoNo",
                "mitumoriKos.name",
                "mitumoriKos.hiyo_kbn",
                "mitumoriKos.biko",
                "mitumoriKos.tani",
                "mitumoriKos.su",
                "mitumoriKos.rom_gaku",
                "mitumoriKos.kansetu_code",
                DB::raw("
                    if(
                        mitumoriKos.hiyo_kbn = 0,
                        mitumoriSais.moto_gaku,
                        mitumoriKos.moto_tanka
                    ) as motoTanka
                "),
                DB::raw("
                    if(
                        mitumoriKos.hiyo_kbn = 0,
                        mitumoriSais.moto_gaku,
                        mitumoriKos.moto_tanka*mitumoriKos.su
                    ) as motoGaku
                "),
                DB::raw("
                    if(
                        mitumoriKos.hiyo_kbn = 0,
                        mitumoriSais.gaku
                        + rom_gaku,
                        mitumoriKos.tanka
                    ) as tanka
                "),
                DB::raw("
                    if(
                        mitumoriKos.hiyo_kbn = 0,
                        mitumoriSais.gaku
                        + rom_gaku,
                        mitumoriKos.tanka*mitumoriKos.su
                    ) as gaku
                ")
            );
            // ->orderBy("mitumoriKos.hiyo_kbn")
            // ->orderBy("mitumoriKos.gyoNo");
        }
        
    }

    public function scopeWithSais($query, $mitumoriId)
    {
        $mitumori = DB::table("mitumoris")->where("id", $mitumoriId)->first();
        $mitumoriSais = DB::table("mitumoriSais as mS")
            ->joinSub(DB::table("mitumoriKos as mK")->where("mitumoriId", $mitumoriId),"mK",function($join){
                $join->on("mK.id",'mS.mitumoriKoId');
            })
            ->where("mS.mitumoriId", $mitumoriId)
            ->select(
                "mitumoriKoId",
                DB::raw("sum(
                    round(cast(mS.su*if(mK.kansetu_auto_calculate = 1 and mS.hiyo_kbn=1,mS.auto_tanka,mS.tanka)as decimal(10,2)))
                    ) as gaku"),
                DB::raw("
                    if('$mitumori->hukugo_tanka_flg'=0,
                        sum(mS.su*mS.bugakariDe*mS.tekkyo_rate),
                        sum(0)
                    )
                     as bugakariDe"),
                DB::raw("
                    if('$mitumori->hukugo_tanka_flg'=0,
                        sum(mS.su*mS.bugakari*mS.tekkyo_rate),
                        sum(0)
                    )
                     as bugakari"),
                DB::raw("
                    if('$mitumori->hukugo_tanka_flg'=0,
                        sum(mS.su*mS.bugakariTo*mS.tekkyo_rate),
                        sum(0)
                    )
                     as bugakariTo")
            )
            ->groupBy("mitumoriKoId");


        //group化しないバージョン
        $mitumoriSais2 = DB::table("mitumoriSais as mS")
            ->where("mS.mitumoriId", $mitumoriId)
            ->joinSub(DB::table("mitumoriKos as mK")->where("mitumoriId", $mitumoriId),"mK",function($join){
                $join->on("mK.id",'mS.mitumoriKoId');
            })
            ->select(
                "mS.mitumoriKoId","mS.name","mS.siyo","mS.su",
                "mS.gyoNo","mS.tani","mS.biko","mS.hiyo_kbn",
                

                DB::raw("case 
                        when '$mitumori->hukugo_tanka_flg'=0 then if(mK.kansetu_auto_calculate = 1 and mS.hiyo_kbn=1,mS.auto_tanka,mS.tanka)
                        when '$mitumori->hukugo_tanka_flg'=1 then 
                            (
                              round(mS.bugakariDe*mS.tekkyo_rate*mK.rom_tankaDe)+
                              round(mS.bugakari*mS.tekkyo_rate*mK.rom_tanka)+
                              round(mS.bugakariTo*mS.tekkyo_rate*mK.rom_tankaTo)+
                              if(mK.kansetu_auto_calculate = 1 and mS.hiyo_kbn=1,mS.auto_tanka,mS.tanka)
                            )
                        end
                         as tanka"),
                
                DB::raw("
                        round(cast(
                            mS.su*(case 
                            when '$mitumori->hukugo_tanka_flg'=0 then if(mK.kansetu_auto_calculate = 1 and mS.hiyo_kbn=1,mS.auto_tanka,mS.tanka)
                            when '$mitumori->hukugo_tanka_flg'=1 then 
                                (
                                round(mS.bugakariDe*mS.tekkyo_rate*mK.rom_tankaDe)+
                                round(mS.bugakari*mS.tekkyo_rate*mK.rom_tanka)+
                                round(mS.bugakariTo*mS.tekkyo_rate*mK.rom_tankaTo)+
                                if(mK.kansetu_auto_calculate = 1 and mS.hiyo_kbn=1,mS.auto_tanka,mS.tanka)
                                )
                            end)
                        as decimal(10,2)))
                         as gaku"),
                DB::raw("mS.su*mS.bugakariDe as bugakariDe"),
                DB::raw("mS.su*mS.bugakari as bugakari"),
                DB::raw("mS.su*mS.bugakariTo as bugakariTo"),
                DB::raw("
                    if(mS.gyoNo is null,
                        mS.name,
                        concat(mS.name,'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',mS.siyo)
                    )as nameSiyo")
            );

        return $query
            ->where("mitumoriKos.mitumoriId", $mitumoriId)
            ->leftJoinSub($mitumoriSais, "mitumoriSais", function ($join) {
                $join->on("mitumoriKos.id", "mitumoriSais.mitumoriKoId");
            })
            ->leftJoinSub($mitumoriSais2, "mitumoriSais2", function ($join) {
                $join->on("mitumoriKos.id", "mitumoriSais2.mitumoriKoId");
            })
            ->select(
                "mitumoriKos.id as mitumoriKoId",
                "mitumoriKos.gyoNo as koGyoNo",
                "mitumoriKos.name as koName",
                "mitumoriKos.hiyo_kbn as koHiyo_kbn",
                "mitumoriKos.biko as koBiko",
                "mitumoriKos.tani as koTani",
                "mitumoriSais2.name",
                "mitumoriSais2.siyo",
                "mitumoriSais2.tanka",
                "mitumoriSais2.su",
                "mitumoriSais2.gyoNo",
                "mitumoriSais2.tani",
                "mitumoriSais2.biko",
                "mitumoriSais2.hiyo_kbn",
                "mitumoriSais2.gaku",
                "mitumoriSais2.nameSiyo",
                DB::raw("
                            if(
                                mitumoriKos.hiyo_kbn = 0,
                                
                                /*見積項目*/
                                mitumoriSais.gaku
                                + 
                                rom_gaku,
                                

                                /*間接費*/
                                mitumoriKos.gaku
                            ) as koGaku
                "),
                DB::raw("if('$mitumori->hukugo_tanka_flg'=0,rom_gaku,0) as koRomGaku")
            )
            ->orderBy("mitumoriKos.hiyo_kbn")
            ->orderBy("mitumoriKos.gyoNo")
            ->orderBy("mitumoriSais2.gyoNo");
    }


    public function scopeWithRomGaku($query,$mitumoriKoId){

        // $mitumori = DB::table("mitumoris")->where("id", $mitumoriId)->first();

        $mitumoriSais=DB::table("mitumoriSais")
        ->where("mitumoriKoId",$mitumoriKoId)
        ->select(
            "mitumoriKoId",
            DB::raw("sum(mitumoriSais.su*mitumoriSais.bugakariDe) as bugakariDe"),
            DB::raw("sum(mitumoriSais.su*mitumoriSais.bugakari) as bugakari"),
            DB::raw("sum(mitumoriSais.su*mitumoriSais.bugakariTo) as bugakariTo")
        )
        ->groupBy('mitumoriKoId');


        return $query->where("id",$mitumoriKoId)
                ->leftJoinSub($mitumoriSais, "mitumoriSais", function ($join) {
                    $join->on("mitumoriKos.id", "mitumoriSais.mitumoriKoId");
                })->select(
                      DB::raw("
                            /*普通の労務費*/
                            /*
                            round(mitumoriSais.bugakariDe * rom_tankaDe)
                            + round(mitumoriSais.bugakari * rom_tanka)
                            + round(mitumoriSais.bugakariTo * rom_tankaTo)
                            */
                            rom_gaku as rom_gaku")
                );

        
    }  


    
    

    
}
