<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Mitumori extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "mitumoris";


    //IDで見積の合計額
    public function scopeWithGaku($query, $id)
    {

        //簡易見積もり
        if(DB::table("mitumoris")->where("id",$id)->first()->kaniFlg==1){
            $rom_gaku=DB::table("mitumoris")->selectRaw("if( mitumoris.hukugo_tanka_flg=1,0,rom_gaku) as rom_gaku")->where("id",$id)->first()->rom_gaku;
            $mitumoriSaisKani = DB::table("mitumoriSais")
                ->where("mitumoriSais.mitumoriId", $id)
                ->select(
                    "mitumoriId",
                    DB::raw("sum(
                            round(cast(
                                mitumoriSais.su*mitumoriSais.tanka
                            as decimal(10,2)))
                        ) as gaku"),
                    DB::raw("sum(mitumoriSais.su*mitumoriSais.bugakariDe*mitumoriSais.tekkyo_rate) as bugakariDe"),
                    DB::raw("sum(mitumoriSais.su*mitumoriSais.bugakari*mitumoriSais.tekkyo_rate) as bugakari"),
                    DB::raw("sum(mitumoriSais.su*mitumoriSais.bugakariTo*mitumoriSais.tekkyo_rate) as bugakariTo"),
                )
                ->groupBy("mitumoriId");

            
            return $query
                ->where("id",$id)
                ->leftJoinSub($mitumoriSaisKani, "mitumoriSaisKani", function ($join) {
                    $join->on("mitumoris.id", "mitumoriSaisKani.mitumoriId");
                })
                ->select(
                    "mitumoris.id",
                    "mitumoris.gyoNo",
                    "mitumoris.title",
                    "mitumoris.code",
                    "mitumoris.area",
                    "mitumoris.torihikiho",
                    "mitumoris.kigen",
                    DB::raw("DATE_FORMAT(do_at,'%Y/%m/%d') as do_at"),
                    DB::raw("sum(
                                    mitumoriSaisKani.gaku
                                    + round(mitumoriSaisKani.bugakariDe * rom_tankaDe)
                                    + round(mitumoriSaisKani.bugakari * rom_tanka)
                                    + round(mitumoriSaisKani.bugakariTo * rom_tankaTo)
                            ) as gaku
                        "),
                    DB::raw("".$rom_gaku." as romGaku")
                )->groupBy(
                    "mitumoris.id",
                    "mitumoris.gyoNo",
                    "mitumoris.title",
                    "mitumoris.code",
                    "mitumoris.area",
                    "mitumoris.torihikiho",
                    "mitumoris.kigen",
                    "mitumoris.aimitu_flg",
                    "do_at"
                );
        
        }else{

            $mitumoriSais = DB::table("mitumoriSais")
            ->where("mitumoriSais.mitumoriId", $id)
            ->select(
                "mitumoriKoId",
                DB::raw("sum(round(cast(mitumoriSais.su*mitumoriSais.tanka)as decimal(10,2))) as gaku"),
                DB::raw("sum(mitumoriSais.su*mitumoriSais.bugakariDe*mitumoriSais.tekkyo_rate) as bugakariDe"),
                DB::raw("sum(mitumoriSais.su*mitumoriSais.bugakari*mitumoriSais.tekkyo_rate) as bugakari"),
                DB::raw("sum(mitumoriSais.su*mitumoriSais.bugakariTo*mitumoriSais.tekkyo_rate) as bugakariTo")
            )
            ->groupBy("mitumoriKoId");
        
            $mitumoriKos = DB::table("mitumoriKos")
                ->where("mitumoriKos.mitumoriId", $id)
                ->leftJoinSub($mitumoriSais, "mitumoriSais", function ($join) {
                    $join->on("mitumoriKos.id", "mitumoriSais.mitumoriKoId");
                })
                ->select(
                    "mitumoriKos.id",
                    "mitumoriKos.mitumoriId",
                    DB::raw("sum(
                            if(
                                mitumoriKos.hiyo_kbn = 0,
                                mitumoriSais.gaku
                                + round(mitumoriSais.bugakariDe * rom_tankaDe)
                                + round(mitumoriSais.bugakari * rom_tanka)
                                + round(mitumoriSais.bugakariTo * rom_tankaTo),
                                mitumoriKos.gaku
                            ) 
                        ) as gaku
                    ")
                )->groupBy(
                    "mitumoriKos.id",
                    "mitumoriKos.mitumoriId"
                );

            return $query
            ->where("mitumoris.id", $id)
            ->leftJoinSub($mitumoriKos, "mitumoriKos", function ($join) {
                    $join->on("mitumoris.id", "mitumoriKos.mitumoriId");
                })
            ->select(
                "mitumoris.id",
                "mitumoris.title",
                "mitumoris.code",
                "mitumoris.area",
                "mitumoris.torihikiho",
                "mitumoris.kigen",
                DB::raw("DATE_FORMAT(do_at,'%Y/%m/%d') as do_at"),
                DB::raw("sum(mitumoriKos.gaku) as gaku"))
                ->groupBy(
                    "mitumoris.id",
                    "mitumoris.title",
                    "mitumoris.code",
                    "mitumoris.area",
                    "mitumoris.torihikiho",
                    "mitumoris.kigen",
                    "do_at"
                );
        }
        
        
    }


    //IDで見積の合計額　間接費無 hiyokbn [0,1,2] 直接費に使ってる
    public function scopeWithGakuNotKansetu($query, $id)
    {

        $m=DB::table("mitumoris")->where("id",$id)->first();

        //簡易見積もり
        if($m->kaniFlg==1){
            
            $mitumoriSaisKani = DB::table("mitumoriSais as mS")
                ->where("mS.mitumoriId", $id)
                ->whereIn("mS.hiyo_kbn",[0,1,2])
                ->select(
                    "mitumoriId",
                    DB::raw("
                        if( '$m->hukugo_tanka_flg'=0,
                            sum(
                                round(cast(
                                    mS.su*if(mS.hiyo_kbn=1 and '$m->kansetu_auto_calculate' = 1,mS.auto_tanka,mS.tanka)
                                AS DECIMAL))
                            )+'$m->rom_gaku',
                            sum(
                                round(cast(
                                    mS.su*(
                                        if(mS.hiyo_kbn=1 and '$m->kansetu_auto_calculate' = 1,mS.auto_tanka,mS.tanka)+
                                        round(cast('$m->rom_tankaDe'*mS.bugakariDe*mS.tekkyo_rate AS DECIMAL))+
                                        round(cast('$m->rom_tanka'*mS.bugakari*mS.tekkyo_rate AS DECIMAL))+
                                        round(cast('$m->rom_tankaTo'*mS.bugakariTo*mS.tekkyo_rate AS DECIMAL))
                                    )
                                 AS DECIMAL))
                            
                            )
                        )
                        as gaku"),
                    
                    //通常単価の歩掛（複合の時は単価に入っているため0になる）
                    DB::raw("if('$m->hukugo_tanka_flg'=0,sum(mS.su*mS.bugakariDe*mS.tekkyo_rate),0) as bugakariDe"),
                    DB::raw("if('$m->hukugo_tanka_flg'=0,sum(mS.su*mS.bugakari*mS.tekkyo_rate),0) as bugakari"),
                    DB::raw("if('$m->hukugo_tanka_flg'=0,sum(mS.su*mS.bugakariTo*mS.tekkyo_rate),0) as bugakariTo"),
                    
                    //法定福利費、算出用の歩掛
                    DB::raw("sum(mS.su*mS.bugakariDe*mS.tekkyo_rate) as bugakariDeForHotei"),
                    DB::raw("sum(mS.su*mS.bugakari*mS.tekkyo_rate) as bugakariForHotei"),
                    DB::raw("sum(mS.su*mS.bugakariTo*mS.tekkyo_rate) as bugakariToForHotei")
                )
                ->groupBy("mitumoriId");
            
            // dd($mitumoriSaisKani->get(),DB::table("mitumoriSais as mS")
            //     ->where("mS.mitumoriId", $id)
            //     ->whereIn("mS.hiyo_kbn",[0,1,2])
            //     ->select(
            //         DB::raw("round(cast(mS.su*(
            //                         if(mS.hiyo_kbn=1 and '$m->kansetu_auto_calculate' = 1,mS.auto_tanka,mS.tanka)+
            //                         round(cast('$m->rom_tankaDe'*mS.bugakariDe*mS.tekkyo_rate AS DECIMAL))+
            //                         round(cast('$m->rom_tanka'*mS.bugakari*mS.tekkyo_rate AS DECIMAL))+
            //                         round(cast('$m->rom_tankaTo'*mS.bugakariTo*mS.tekkyo_rate AS DECIMAL))
            //                     ) AS DECIMAL))")
            //     )
            //     ->get());
            
            return $query
                ->where("id",$id)
                ->leftJoinSub($mitumoriSaisKani, "mitumoriSaisKani", function ($join) {
                    $join->on("mitumoris.id", "mitumoriSaisKani.mitumoriId");
                })
                ->select(
                    "mitumoris.id",
                    "mitumoris.gyoNo",
                    "mitumoris.title",
                    "mitumoris.code",
                    "mitumoris.area",
                    "mitumoris.torihikiho",
                    "mitumoris.kigen",
                    DB::raw("DATE_FORMAT(do_at,'%Y/%m/%d') as do_at"),
                    DB::raw("(
                                    mitumoriSaisKani.gaku
                                    /*+ round(cast(mitumoriSaisKani.bugakariDe * rom_tankaDe AS DECIMAL)) 複合単価なら労務費はもう入っている
                                    + round(cast(mitumoriSaisKani.bugakari * rom_tanka AS DECIMAL)) 通常の単価でもすでに労務費は入っているため使わない。
                                    + round(cast(mitumoriSaisKani.bugakariTo * rom_tankaTo AS DECIMAL))*/
                            ) as gaku
                        "),
                    //法定福利費用の労務額
                    DB::raw("
                                if(mitumoris.hukugo_tanka_flg=0,
                                    mitumoris.rom_gaku,
                                    round(cast(mitumoriSaisKani.bugakariDeForHotei * rom_tankaDe AS DECIMAL))
                                    + round(cast(mitumoriSaisKani.bugakariForHotei * rom_tanka AS DECIMAL))
                                    + round(cast(mitumoriSaisKani.bugakariToForHotei * rom_tankaTo AS DECIMAL))
                                )
                                as romGakuForHotei
                        "),

                    //建設業退職金共済掛金の歩掛
                    DB::raw("
                            mitumoriSaisKani.bugakariDeForHotei + 
                            mitumoriSaisKani.bugakariForHotei + 
                            mitumoriSaisKani.bugakariToForHotei as bugakariForKen
                        ")
                );
        
        }else{

            $mitumoriSais = DB::table("mitumoriSais as mS")
                ->join("mitumoriKos as mK",function($join)use($id){
                    $join->on("mK.id", "mS.mitumoriKoId")
                    ->where("mK.mitumoriId", $id);
                })
                ->where("mS.mitumoriId", $id)
                ->whereIn("mS.hiyo_kbn",[0,1,2])
                ->select(
                    "mitumoriKoId",
                    DB::raw("
                        if( '$m->hukugo_tanka_flg'=0,
                            sum(
                                round(cast(
                                    mS.su*if(mS.hiyo_kbn=1 and mK.kansetu_auto_calculate = 1,mS.auto_tanka,mS.tanka)
                                AS DECIMAL))
                            )+mK.rom_gaku,
                            sum(
                                round(cast(mS.su*(
                                    if(mS.hiyo_kbn=1 and mK.kansetu_auto_calculate = 1,mS.auto_tanka,mS.tanka)+
                                    round(cast(mK.rom_tankaDe*mS.bugakariDe*mS.tekkyo_rate AS DECIMAL))+
                                    round(cast(mK.rom_tanka*mS.bugakari*mS.tekkyo_rate AS DECIMAL))+
                                    round(cast(mK.rom_tankaTo*mS.bugakariTo*mS.tekkyo_rate AS DECIMAL))
                                ) AS DECIMAL))
                            )
                        )
                        as gaku"),
                    //労務額（法定福利費の計算用なので複合単価フラグ関係なしに０にすることはない）
                    DB::raw("sum(mS.su*mS.bugakariDe*mS.tekkyo_rate) as bugakariDeForHotei"),
                    DB::raw("sum(mS.su*mS.bugakari*mS.tekkyo_rate) as bugakariForHotei"),
                    DB::raw("sum(mS.su*mS.bugakariTo*mS.tekkyo_rate) as bugakariToForHotei")
                
                )
                ->groupBy("mitumoriKoId","mK.rom_gaku");
        
            
            
        
            $mitumoriKos = DB::table("mitumoriKos")
                ->where("mitumoriKos.mitumoriId", $id)
                ->whereIn("mitumoriKos.hiyo_kbn",[0,2])
                ->leftJoinSub($mitumoriSais, "mitumoriSais", function ($join) {
                    $join->on("mitumoriKos.id", "mitumoriSais.mitumoriKoId");
                })
                ->select(
                    "mitumoriKos.id",
                    "mitumoriKos.mitumoriId",
                    DB::raw("(
                            (case
                                when mitumoriKos.hiyo_kbn = 0 then
                                    mitumoriSais.gaku   
                                when mitumoriKos.hiyo_kbn = 2 then
                                    mitumoriKos.su*mitumoriKos.tanka
                                end
                            ) 
                        ) as gaku
                    "),

                    //法定福利費用の労務額
                    DB::raw("(
                            if('$m->hukugo_tanka_flg'=0,
                                rom_gaku,
                                round(cast(mitumoriSais.bugakariDeForHotei * rom_tankaDe AS DECIMAL))
                                + round(cast(mitumoriSais.bugakariForHotei * rom_tanka AS DECIMAL))
                                + round(cast(mitumoriSais.bugakariToForHotei * rom_tankaTo AS DECIMAL))
                            )
                    ) as romGakuForHotei
                    "),

                    //建設業退職金共済掛金の歩掛
                    DB::raw("
                            mitumoriSais.bugakariDeForHotei + 
                            mitumoriSais.bugakariForHotei + 
                            mitumoriSais.bugakariToForHotei as bugakariForKen
                        ")
                );

            // dd($mitumoriKos->get(),$mitumoriSais->get());

            return $query
            ->where("mitumoris.id", $id)
            ->leftJoinSub($mitumoriKos, "mitumoriKos", function ($join) {
                    $join->on("mitumoris.id", "mitumoriKos.mitumoriId");
                })
            ->select(
                "mitumoris.id",
                "mitumoris.title",
                "mitumoris.code",
                "mitumoris.area",
                "mitumoris.torihikiho",
                "mitumoris.kigen",
                DB::raw("sum(mitumoriKos.bugakariForKen) as bugakariForKen"),
                DB::raw("DATE_FORMAT(do_at,'%Y/%m/%d') as do_at"),
                DB::raw("sum(mitumoriKos.gaku) as gaku"),
                DB::raw("sum(mitumoriKos.romGakuForHotei) as romGakuForHotei"))
                ->groupBy(
                    "mitumoris.id",
                    "mitumoris.title",
                    "mitumoris.code",
                    "mitumoris.area",
                    "mitumoris.torihikiho",
                    "mitumoris.kigen",
                    "do_at",
                );
        }
        
        
    }




    //取引先ごと
    public function scopeWithWhereTorihikisaki($query, $torihikisaki)
    {
        $mitumoriIds=DB::table("mitumoris")
        ->where("aimitu_flg",0)
        ->where("groupId",$torihikisaki)
        ->pluck('id');
        

        $mitumoriSais = DB::table("mitumoriSais as mS")
            ->join("mitumoriKos as mK",function($join){
                $join->on("mK.id","mS.mitumoriKoId");
            })
            ->join("mitumoris as mt",function($join){
                $join->on("mt.id","mK.mitumoriId");
            })
            ->whereIn("mS.mitumoriId", $mitumoriIds)
            ->whereNotNull("mS.mitumoriKoId")
            ->select(
                "mitumoriKoId",
                DB::raw("
                        if( mt.hukugo_tanka_flg=0,
                            sum(round(cast(mS.su*if(mK.kansetu_auto_calculate = 1 and mS.hiyo_kbn=1,mS.auto_tanka,mS.tanka) AS DECIMAL)))+mK.rom_gaku,
                            sum(
                                round(cast(mS.su*(
                                    if(mS.hiyo_kbn=1 and mK.kansetu_auto_calculate = 1,mS.auto_tanka,mS.tanka)+
                                    round(cast(mK.rom_tankaDe*mS.bugakariDe*mS.tekkyo_rate AS DECIMAL))+
                                    round(cast(mK.rom_tanka*mS.bugakari*mS.tekkyo_rate AS DECIMAL))+
                                    round(cast(mK.rom_tankaTo*mS.bugakariTo*mS.tekkyo_rate AS DECIMAL))
                                ) AS DECIMAL))
                            )
                        )
                        as gaku"),
                DB::raw("if(mt.hukugo_tanka_flg=0,sum(mS.su*mS.bugakariDe*mS.tekkyo_rate),0) as bugakariDe"),
                DB::raw("if(mt.hukugo_tanka_flg=0,sum(mS.su*mS.bugakari*mS.tekkyo_rate),0) as bugakari"),
                DB::raw("if(mt.hukugo_tanka_flg=0,sum(mS.su*mS.bugakariTo*mS.tekkyo_rate),0) as bugakariTo")
            )
            ->groupBy("mS.mitumoriKoId","mt.hukugo_tanka_flg","mK.rom_gaku");
        
        $mitumoriKos = DB::table("mitumoriKos as mK")
            ->whereIn("mK.mitumoriId", $mitumoriIds)
            ->Join("mitumoris as mt", function ($join) {
                $join->on("mt.id", "mK.mitumoriId");
            })
            ->leftJoinSub($mitumoriSais, "mS", function ($join) {
                $join->on("mK.id", "mS.mitumoriKoId");
            })
            ->select(
                "mK.mitumoriId",
                DB::raw("sum(
                        
                        case 
                        when mK.hiyo_kbn = 0 then 
                            mS.gaku
                            /*+ round(cast(mS.bugakariDe * mK.rom_tankaDe AS DECIMAL))
                            + round(cast(mS.bugakari * mK.rom_tanka AS DECIMAL))
                            + round(cast(mS.bugakariTo * mK.rom_tankaTo AS DECIMAL))*/

                        when mK.hiyo_kbn in (2,4,5) then
                            mK.tanka*mK.su

                        when mK.hiyo_kbn = 3 then
                            if(mt.kansetu_auto_calculate = 1 and mK.hiyo_kbn=3,mK.auto_tanka,mK.tanka)

                        end 
                    ) as gaku
                ")
            )->groupBy("mK.mitumoriId");

        $mitumoriSaisKani = DB::table("mitumoriSais as mS")
            ->Join("mitumoris as mt", function ($join) {
                $join->on("mt.id", "mS.mitumoriId")
                ->where("mt.kaniFlg",1);
            })
            ->whereIn("mS.mitumoriId", $mitumoriIds)
            ->whereNull("mS.mitumoriKoId")
            ->select(
                "mS.mitumoriId",
                DB::raw("
                        if( mt.hukugo_tanka_flg=0,
                            sum(
                                round(cast(mS.su*if(mt.kansetu_auto_calculate = 1 and mS.hiyo_kbn in(1,3),mS.auto_tanka,mS.tanka) AS DECIMAL)))+mt.rom_gaku,
                            sum(
                                round(cast(
                                    mS.su*(
                                        if(mS.hiyo_kbn in(1,3) and mt.kansetu_auto_calculate=1 ,mS.auto_tanka,mS.tanka)+
                                        round(cast(mt.rom_tankaDe*mS.bugakariDe*mS.tekkyo_rate AS DECIMAL))+
                                        round(cast(mt.rom_tanka*mS.bugakari*mS.tekkyo_rate AS DECIMAL))+
                                        round(cast(mt.rom_tankaTo*mS.bugakariTo*mS.tekkyo_rate AS DECIMAL))
                                    )
                                AS DECIMAL))
                            )
                        )
                        as gaku"),
                DB::raw("if(mt.hukugo_tanka_flg=0,sum(mS.su*mS.bugakariDe*mS.tekkyo_rate),0) as bugakariDe"),
                DB::raw("if(mt.hukugo_tanka_flg=0,sum(mS.su*mS.bugakari*mS.tekkyo_rate),0) as bugakari"),
                DB::raw("if(mt.hukugo_tanka_flg=0,sum(mS.su*mS.bugakariTo*mS.tekkyo_rate),0) as bugakariTo")
            )
            ->groupBy("mS.mitumoriId","mt.hukugo_tanka_flg","mt.rom_gaku");
        
        $mitumoriKani=DB::table("mitumoris as mt")
        ->where("mt.groupId",$torihikisaki)
        ->where("mt.aimitu_flg",0)
        ->where("mt.kaniFlg",1)
        ->whereNull("mt.deleted_at")
        ->leftJoinSub($mitumoriSaisKani, "mitumoriSaisKani", function ($join) {
            $join->on("mt.id", "mitumoriSaisKani.mitumoriId");
        })
        ->select(
            "mt.id",
            "mt.gyoNo",
            "mt.title",
            "mt.keisyo",
            "mt.atesaki",
            "mt.code",
            "mt.area",
            "mt.torihikiho",
            "mt.kigen",
             DB::raw("DATE_FORMAT(
                    COALESCE(print_at,'1900/01/01')
                    ,'%Y/%m/%d') as do_at"),
            DB::raw("COALESCE(sum(
                            mitumoriSaisKani.gaku
                            /*+ round(mitumoriSaisKani.bugakariDe * rom_tankaDe)
                            + round(mitumoriSaisKani.bugakari * rom_tanka)
                            + round(mitumoriSaisKani.bugakariTo * rom_tankaTo)*/
                    ),0) as gaku
                ")
        )->groupBy(
            "mt.id",
            "mt.gyoNo",
            "mt.title",
            "mt.keisyo",
            "mt.atesaki",
            "mt.code",
            "mt.area",
            "mt.torihikiho",
            "mt.kigen",
            "print_at"
        );

        return $query
        ->where("groupId",$torihikisaki)
        ->where("aimitu_flg",0)
        ->where("kaniFlg",0)
        ->leftJoinSub($mitumoriKos, "mitumoriKos", function ($join) {
                $join->on("mitumoris.id", "mitumoriKos.mitumoriId");
            })
        ->select(
            "mitumoris.id",
            "mitumoris.gyoNo",
            "mitumoris.title",
            "mitumoris.keisyo",
            "mitumoris.atesaki",
            "mitumoris.code",
            "mitumoris.area",
            "mitumoris.torihikiho",
            "mitumoris.kigen",
             DB::raw("DATE_FORMAT(
                    COALESCE(print_at,'1900/01/01')
                    ,'%Y/%m/%d') as do_at"),
            DB::raw("COALESCE(mitumoriKos.gaku,0) as gaku")
            )
        ->unionAll($mitumoriKani);
    }


    public function scopeWithLikeName($query, $name){
        $mitumoriIds=DB::table("mitumoris")->where("aimitu_flg",0)->where("title","like","%".$name."%")->pluck('id');
        $mitumoriSais = DB::table("mitumoriSais as mS")
            ->join("mitumoriKos as mK",function($join){
                $join->on("mK.id","mS.mitumoriKoId");
            })
            ->join("mitumoris as mt",function($join){
                $join->on("mt.id","mK.mitumoriId");
            })
            ->whereIn("mS.mitumoriId", $mitumoriIds)
            ->whereNotNull("mS.mitumoriKoId")
            ->select(
                "mitumoriKoId",
                DB::raw("
                        if( mt.hukugo_tanka_flg=0,
                            sum(round(cast(mS.su*if(mK.kansetu_auto_calculate = 1 and mS.hiyo_kbn=1,mS.auto_tanka,mS.tanka)AS DECIMAL)))+mK.rom_gaku,
                            sum(
                                round(cast(
                                    mS.su*(
                                        if(mS.hiyo_kbn=1 and mK.kansetu_auto_calculate = 1,mS.auto_tanka,mS.tanka)+
                                        round(cast(mK.rom_tankaDe*mS.bugakariDe*mS.tekkyo_rate AS DECIMAL))+
                                        round(cast(mK.rom_tanka*mS.bugakari*mS.tekkyo_rate AS DECIMAL))+
                                        round(cast(mK.rom_tankaTo*mS.bugakariTo*mS.tekkyo_rate AS DECIMAL))
                                    )
                                AS DECIMAL))
                            )
                        )
                        as gaku"),
                DB::raw("if(mt.hukugo_tanka_flg=0,sum(mS.su*mS.bugakariDe*mS.tekkyo_rate),0) as bugakariDe"),
                DB::raw("if(mt.hukugo_tanka_flg=0,sum(mS.su*mS.bugakari*mS.tekkyo_rate),0) as bugakari"),
                DB::raw("if(mt.hukugo_tanka_flg=0,sum(mS.su*mS.bugakariTo*mS.tekkyo_rate),0) as bugakariTo")
            
            )
            ->groupBy("mS.mitumoriKoId","mt.hukugo_tanka_flg","mK.rom_gaku");
        
        $mitumoriKos = DB::table("mitumoriKos as mK")
            ->whereIn("mK.mitumoriId", $mitumoriIds)
            ->Join("mitumoris as mt", function ($join) {
                $join->on("mt.id", "mK.mitumoriId");
            })
            ->leftJoinSub($mitumoriSais, "mS", function ($join) {
                $join->on("mK.id", "mS.mitumoriKoId");
            })
            ->select(
                "mK.mitumoriId",
                DB::raw("sum(
                        
                        case 
                        when mK.hiyo_kbn = 0 then 
                            mS.gaku
                            /*+ round(cast(mS.bugakariDe * mK.rom_tankaDe AS DECIMAL))
                            + round(cast(mS.bugakari * mK.rom_tanka AS DECIMAL))
                            + round(cast(mS.bugakariTo * mK.rom_tankaTo AS DECIMAL))*/

                        when mK.hiyo_kbn in (2,4,5) then
                            mK.tanka*mK.su

                        when mK.hiyo_kbn = 3 then
                            if(mt.kansetu_auto_calculate = 1 and mK.hiyo_kbn=3,mK.auto_tanka,mK.tanka)

                        end 
                    ) as gaku
                ")
            )->groupBy("mK.mitumoriId");

        $mitumoriSaisKani = DB::table("mitumoriSais as mS")
            ->Join("mitumoris as mt", function ($join) {
                $join->on("mt.id", "mS.mitumoriId")
                ->where("mt.kaniFlg",1);
            })
            ->whereIn("mS.mitumoriId", $mitumoriIds)
            ->whereNull("mS.mitumoriKoId")
            ->select(
                "mS.mitumoriId",
                DB::raw("
                        if( mt.hukugo_tanka_flg=0,
                            sum(round(cast(mS.su*if(mt.kansetu_auto_calculate = 1 and mS.hiyo_kbn in(1,3),mS.auto_tanka,mS.tanka)AS DECIMAL)))+mt.rom_gaku,
                            sum(
                                round(cast(
                                    mS.su*(
                                        if(mS.hiyo_kbn in(1,3) and mt.kansetu_auto_calculate=1 ,mS.auto_tanka,mS.tanka)+
                                        round(cast(mt.rom_tankaDe*mS.bugakariDe*mS.tekkyo_rate AS DECIMAL))+
                                        round(cast(mt.rom_tanka*mS.bugakari*mS.tekkyo_rate AS DECIMAL))+
                                        round(cast(mt.rom_tankaTo*mS.bugakariTo*mS.tekkyo_rate AS DECIMAL))
                                    )
                                AS DECIMAL))
                            )
                        )
                        as gaku"),
                DB::raw("if(mt.hukugo_tanka_flg=0,sum(mS.su*mS.bugakariDe*mS.tekkyo_rate),0) as bugakariDe"),
                DB::raw("if(mt.hukugo_tanka_flg=0,sum(mS.su*mS.bugakari*mS.tekkyo_rate),0) as bugakari"),
                DB::raw("if(mt.hukugo_tanka_flg=0,sum(mS.su*mS.bugakariTo*mS.tekkyo_rate),0) as bugakariTo")
            )
            ->groupBy("mS.mitumoriId","mt.hukugo_tanka_flg","mt.rom_gaku");
        
        $mitumoriKani=DB::table("mitumoris")
        ->where("title","like","%".$name."%")
        ->where("aimitu_flg",0)
        ->where("kaniFlg",1)
        ->leftJoinSub($mitumoriSaisKani, "mitumoriSaisKani", function ($join) {
            $join->on("mitumoris.id", "mitumoriSaisKani.mitumoriId");
        })
        ->select(
            "mitumoris.id",
            "mitumoris.groupId",
            "mitumoris.gyoNo",
            "mitumoris.title",
            "mitumoris.atesaki",
            "mitumoris.keisyo",
            "mitumoris.code",
            "mitumoris.area",
            "mitumoris.torihikiho",
            "mitumoris.kigen",
            DB::raw("DATE_FORMAT(
                    COALESCE(print_at,'1900/01/01')
                    ,'%Y/%m/%d') as do_at"),
            DB::raw("sum(
                            mitumoriSaisKani.gaku
                            /*+ round(cast(mitumoriSaisKani.bugakariDe * rom_tankaDe AS DECIMAL))
                            + round(cast(mitumoriSaisKani.bugakari * rom_tanka AS DECIMAL))
                            + round(cast(mitumoriSaisKani.bugakariTo * rom_tankaTo AS DECIMAL))*/
                    ) as gaku
                ")
        )->groupBy(
            "mitumoris.id",
            "mitumoris.groupId",
            "mitumoris.gyoNo",
            "mitumoris.title",
            "mitumoris.atesaki",
            "mitumoris.keisyo",
            "mitumoris.code",
            "mitumoris.area",
            "mitumoris.torihikiho",
            "mitumoris.kigen",
            "print_at"
        );

        return $query
        ->where("title","like","%".$name."%")
        ->where("aimitu_flg",0)
        ->where("kaniFlg",0)
        ->leftJoinSub($mitumoriKos, "mitumoriKos", function ($join) {
                $join->on("mitumoris.id", "mitumoriKos.mitumoriId");
            })
        ->select(
            "mitumoris.id",
            "mitumoris.groupId",
            "mitumoris.gyoNo",
            "mitumoris.title",
            "mitumoris.atesaki",
            "mitumoris.keisyo",
            "mitumoris.code",
            "mitumoris.area",
            "mitumoris.torihikiho",
            "mitumoris.kigen",
             DB::raw("DATE_FORMAT(
                    COALESCE(print_at,'1900/01/01')
                    ,'%Y/%m/%d') as do_at"),
            DB::raw("COALESCE(mitumoriKos.gaku,0) as gaku")
            )
        ->unionAll($mitumoriKani);

    }


    public function scopePdfStyle($query, $id){
        $mitumori=$query->where("id",$id)->first();

        //atesakiの文字数によってフォントサイズと位置を変える
        if((mb_strwidth($mitumori->atesaki,'UTF-8')/2)>=18 && (mb_strwidth($mitumori->atesaki,'UTF-8')/2)<=25){
            $mitumori->atesaki="<span style='font-size:14px!important; position: absolute; left:0px; top:7px;'>".$mitumori->atesaki."</span>";
        }elseif((mb_strwidth($mitumori->atesaki,'UTF-8')/2)>=26){
            $atesakiArray=mb_str_split($mitumori->atesaki,25);
            $mitumori->atesaki="<span style='font-size:14px!important; position: absolute; left:0px; top:-2px;'>".$atesakiArray[0]."</span>"
            ."<span style='font-size:14px!important; position: absolute; left:0px; top:10px;'>".($atesakiArray[1]??'')."</span>";
        }

        //titleの文字数によってフォントサイズと位置を変える
        if((mb_strwidth($mitumori->title,'UTF-8')/2)>=18 && (mb_strwidth($mitumori->title,'UTF-8')/2)<=24){
            $mitumori->title="<span style='font-size:13px!important; position: absolute; left:70px; top:4px;'>".$mitumori->title."</span>";
        }elseif((mb_strwidth($mitumori->title,'UTF-8')/2)>24){
            $titleArray=mb_str_split($mitumori->title,24);
            $mitumori->title="<span style='font-size:13px!important; position: absolute; left:70px; top:-2px;'>".$titleArray[0]."</span>"
            ."<span style='font-size:13px!important; position: absolute; left:70px; top:10px;'>".$titleArray[1]."</span>";
        }

        //areaの文字数によってフォントサイズと位置を変える
        if((mb_strwidth($mitumori->area,'UTF-8')/2)>=18 && (mb_strwidth($mitumori->area,'UTF-8')/2)<=24){
            $mitumori->area="<span style='font-size:13px!important; position: absolute; left:70px; top:4px;'>".$mitumori->area."</span>";
        }elseif((mb_strwidth($mitumori->area,'UTF-8')/2)>24){
            $areaArray=mb_str_split($mitumori->area,24);
            $mitumori->area="<span style='font-size:13px!important; position: absolute; left:70px; top:-2px;'>".$areaArray[0]."</span>"
            ."<span style='font-size:13px!important; position: absolute; left:70px; top:10px;'>".$areaArray[1]."</span>";
        }

        //取引法の文字数によってフォントサイズと位置を変える
        if((mb_strwidth($mitumori->torihikiho,'UTF-8')/2)>=18 && (mb_strwidth($mitumori->torihikiho,'UTF-8')/2)<=24){
            $mitumori->torihikiho="<span style='font-size:13px!important; position: absolute; left:70px; top:4px;'>".$mitumori->torihikiho."</span>";
        }elseif((mb_strwidth($mitumori->torihikiho,'UTF-8')/2)>24){
            $torihikihoArray=mb_str_split($mitumori->torihikiho,24);
            $mitumori->torihikiho="<span style='font-size:13px!important; position: absolute; left:70px; top:-2px;'>".$torihikihoArray[0]."</span>"
            ."<span style='font-size:13px!important; position: absolute; left:70px; top:10px;'>".$torihikihoArray[1]."</span>";
        }

        //期限の文字数によってフォントサイズと位置を変える
        if((mb_strwidth($mitumori->kigen,'UTF-8')/2)>=18 && (mb_strwidth($mitumori->kigen,'UTF-8')/2)<=24){
            $mitumori->kigen="<span style='font-size:13px!important; position: absolute; left:70px; top:4px;'>".$mitumori->kigen."</span>";
        }elseif((mb_strwidth($mitumori->kigen,'UTF-8')/2)>24){
            $kigenArray=mb_str_split($mitumori->kigen,24);
            $mitumori->kigen="<span style='font-size:13px!important; position: absolute; left:70px; top:-2px;'>".$kigenArray[0]."</span>"
            ."<span style='font-size:13px!important; position: absolute; left:70px; top:10px;'>".$kigenArray[1]."</span>";
        }

        return $mitumori;
    }
}
