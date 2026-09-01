<?php
 
namespace App\Services;
use Illuminate\Support\Facades\DB;

use carbon\Carbon;
use App\Models\Mitumori;

class RekiService
{
    public function rekiStore($id,$past_or_future,$kakutei_flg=0){
        
        list($reki,$rekiK,$rekiS)=$this->topRekiGet($past_or_future);
        $reki=json_decode(json_encode($reki),true);
        $rekiK=json_decode(json_encode($rekiK),true);
        $rekiS=json_decode(json_encode($rekiS),true);

        $mitumori=json_decode(json_encode(DB::table("mitumoris")->where("id",$id)->first()),true);
        $mitumoriKos=json_decode(json_encode(DB::table("mitumoriKos")->where("mitumoriId",$id)->get()->toArray()),true);
        $mitumoriSais=json_decode(json_encode(DB::table("mitumoriSais")->where("mitumoriId",$id)->get()->toArray()),true);
        $maxRekiNo=DB::table("mitumoriRekis")->where("reki_url",$_SERVER['HTTP_REFERER'])->where("past_or_future",$past_or_future)->max("reki_no")??0;

        if(isset($mitumoriKos)){
            foreach($mitumoriKos as &$ko){
                $ko["reki_no"]=$maxRekiNo+1;
                $ko["reki_url"]=$_SERVER['HTTP_REFERER'];
                $ko["past_or_future"]=$past_or_future;  
            }
            DB::table("mitumoriKoRekis")->insert($mitumoriKos);
        }

        if(isset($mitumoriSais)){
            foreach($mitumoriSais as &$sa){
                $sa["reki_no"]=$maxRekiNo+1;
                $sa["reki_url"]=$_SERVER['HTTP_REFERER'];
                $sa["past_or_future"]=$past_or_future;  
            }
            DB::table("mitumoriSaiRekis")->insert($mitumoriSais);
        }
        

        $mitumori=json_decode(json_encode(DB::table("mitumoris")->where("id",$id)->first()),true);
        
        $mitumori["reki_no"]=$maxRekiNo+1;
        $mitumori["reki_url"]=$_SERVER['HTTP_REFERER'];
        $mitumori["past_or_future"]=$past_or_future;  
        $mitumori["kakutei_flg"]=$kakutei_flg;

        DB::table("mitumoriRekis")->insert($mitumori);
    }

    //一番上のデータだけ消す
    public function topRekiGet($past_or_future){


        $maxNo=DB::table("mitumoriRekis")
        ->where("past_or_future",$past_or_future)
        ->where("kakutei_flg",1)
        ->where("reki_url",$_SERVER['HTTP_REFERER'])
        ->max("reki_no");

        $rekiKos=DB::table("mitumoriKoRekis")
        ->select(DB::getSchemaBuilder()->getColumnListing("mitumoriKos"))
        ->where("reki_url",$_SERVER['HTTP_REFERER'])
        ->where("past_or_future",$past_or_future)
        ->where("reki_no",$maxNo)
        ->get();

        $rekiSais=DB::table("mitumoriSaiRekis")
        ->select(DB::getSchemaBuilder()->getColumnListing("mitumoriSais"))
        ->where("reki_url",$_SERVER['HTTP_REFERER'])
        ->where("past_or_future",$past_or_future)
        ->where("reki_no",$maxNo)
        ->get();

        $reki=DB::table("mitumoriRekis")
        ->select(DB::getSchemaBuilder()->getColumnListing("mitumoris"))
        ->where("reki_url",$_SERVER['HTTP_REFERER'])
        ->where("past_or_future",$past_or_future)
        ->where("reki_no",$maxNo)
        ->first();

        return [$reki,$rekiKos,$rekiSais];
    }




    public function rekiDelete($past_or_future){
        DB::table("mitumoriKoRekis")
        ->where("reki_url",$_SERVER['HTTP_REFERER'])
        ->where("past_or_future",$past_or_future)
        ->delete();

        DB::table("mitumoriSaiRekis")
        ->where("reki_url",$_SERVER['HTTP_REFERER'])
        ->where("past_or_future",$past_or_future)
        ->delete();

        DB::table("mitumoriRekis")
        ->where("reki_url",$_SERVER['HTTP_REFERER'])
        ->where("past_or_future",$past_or_future)
        ->delete();
    }

    //一番上のデータだけ消す
    public function topRekiDelete($past_or_future){
        $maxNo=DB::table("mitumoriRekis")
        ->where("past_or_future",$past_or_future)
        ->where("reki_url",$_SERVER['HTTP_REFERER'])
        ->where("kakutei_flg",1)
        ->max("reki_no");

        DB::table("mitumoriKoRekis")
        ->where("reki_url",$_SERVER['HTTP_REFERER'])
        ->where("past_or_future",$past_or_future)
        ->where("reki_no",$maxNo)
        ->delete();

        DB::table("mitumoriSaiRekis")
        ->where("reki_url",$_SERVER['HTTP_REFERER'])
        ->where("past_or_future",$past_or_future)
        ->where("reki_no",$maxNo)
        ->delete();

        DB::table("mitumoriRekis")
        ->where("reki_url",$_SERVER['HTTP_REFERER'])
        ->where("past_or_future",$past_or_future)
        ->where("reki_no",$maxNo)
        ->delete();
    }

    


    public function checkReki($url=null){
        
        if($url==null) $url = $_SERVER['HTTP_REFERER'];
        
        $future=DB::table("mitumoriRekis")
        ->where("reki_url",$url)
        ->where("past_or_future","future")
        ->exists();
        $past=DB::table("mitumoriRekis")
        ->where("reki_url",$url)
        ->where("past_or_future","past")
        ->where("kakutei_flg",1)
        ->exists();

        return [$future,$past];
        

    }



    //戻る進をした時に単価や歩掛が変われば
    //同じ見積り内の同じ資材も同時に合わせる
    public function sizaiToMatch($mKId){

        $mss=DB::table("mitumoriSais")
        ->where("mitumoriKoId",$mKId)
        ->where("tanka_rendo_code","!=","")
        ->where("bugakari_rendo_code","!=","")
        ->where("hiyo_kbn",0)
        ->get();

        foreach($mss as $ms){
            DB::table("mitumoriSais")
                ->where("id",'!=',$ms->id)
                ->where("mitumoriId",$ms->mitumoriId)
                ->where("tanka_rendo_code",$ms->tanka_rendo_code)
                ->update(["tanka"=>$ms->tanka]);
            DB::table("mitumoriSais")
                ->where("id",'!=',$ms->id)
                ->where("mitumoriId",$ms->mitumoriId)
                ->where("bugakari_rendo_code",$ms->bugakari_rendo_code)
                ->update([
                    'bugakariDe'=>$ms->bugakariDe,
                    'bugakari'=>$ms->bugakari,
                    'bugakariTo'=>$ms->bugakariTo,
                ]);
        }

    }


    //項目直近で同じだった場合は消す
    public function MatchKoDelete($past_or_future,$url=null){

        if($url=null) $_SERVER['HTTP_REFERER'];

        $reki=DB::table("mitumoriRekis")
        ->where("reki_url",$url)
        ->where("past_or_future",$past_or_future)
        ->where("kakutei_flg",1)
        ->orderBy("reki_no","desc")
        ->get();

        
        //データがないなら処理しない
        if(!isset($reki[0])) return;
        

        $kos=DB::table("mitumoriKos")
        ->where("mitumoriId",$reki[0]->id)
        ->orderBy('id')
        ->get()->map(function ($row) {
            unset($row->created_at, $row->updated_at);
            return $row;
        });
        $m=DB::table("mitumoris")
        ->where("id",$reki[0]->id)
        ->first();

        // dd($m);

        foreach ($reki as $key => $value) {
            $rekikos=DB::table("mitumoriKoRekis")
            ->select(DB::getSchemaBuilder()->getColumnListing("mitumoriKos"))
            ->where("reki_url",$url)
            ->where("past_or_future",$past_or_future)
            ->where("reki_no",$value->reki_no)
            ->orderBy('id')
            ->get()->map(function ($row) {
                unset($row->created_at, $row->updated_at);
                return $row;
            });
            
            if($rekikos == $kos && $value->kansetu_auto_calculate == $m->kansetu_auto_calculate){
                dd($rekikos == $kos && $value->kansetu_auto_calculate == $m->kansetu_auto_calculate);
                DB::table("mitumoriKoRekis")
                ->where("reki_url",$url)
                ->where("past_or_future",$past_or_future)
                ->where("reki_no",$value->reki_no)
                ->delete();

                DB::table("mitumoriSaiRekis")
                ->where("reki_url",$url)
                ->where("past_or_future",$past_or_future)
                ->where("reki_no",$value->reki_no)
                ->delete();

                DB::table("mitumoriRekis")
                ->where("reki_url",$url)
                ->where("past_or_future",$past_or_future)
                ->where("reki_no",$value->reki_no)
                ->delete();
            }else{
                break;
            }
            
        }       
    }


     //項目直近で同じだった場合は消す
    public function MatchSaiDelete($koId,$past_or_future,$url=null){

        if($url==null) $url = $_SERVER['HTTP_REFERER'];
        
        $reki=DB::table("mitumoriRekis")
        ->where("reki_url",$url)
        ->where("past_or_future",$past_or_future)
        ->where("kakutei_flg",1)
        ->orderBy("reki_no","desc")
        ->get();

        //データがないなら処理しない
        if(!isset($reki[0])) return;

        $sais=DB::table("mitumoriSais")
        ->where("mitumoriKoId",$koId)
        ->orderBy('id')
        ->get()->map(function ($row) {
            unset($row->created_at, $row->updated_at);
            return $row;
        });

        $ko=DB::table("mitumoriKos")
        ->where("id",$koId)
        ->orderBy('id')
        ->first();
        unset($ko->created_at, $ko->updated_at);

        foreach ($reki as $key => $value) {
            $rekisais=DB::table("mitumoriSaiRekis")
            ->select(DB::getSchemaBuilder()->getColumnListing("mitumoriSais"))
            ->where("reki_url",$url)
            ->where("past_or_future",$past_or_future)
            ->where("reki_no",$value->reki_no)
            ->where("mitumoriKoId",$koId)
            ->orderBy('id')
            ->get()->map(function ($row) {
                unset($row->created_at, $row->updated_at);
                return $row;
            });

            
            $koReki=DB::table("mitumoriKoRekis")
                ->where("id",$koId)
                ->select(DB::getSchemaBuilder()->getColumnListing("mitumoriKos"))
                ->where("reki_url",$url)
                ->where("past_or_future",$past_or_future)
                ->where("reki_no",$value->reki_no)
                ->first();
            unset($koReki->created_at,$koReki->updated_at);

            if($rekisais == $sais && $ko == $koReki){
                DB::table("mitumoriKoRekis")
                ->where("reki_url",$url)
                ->where("past_or_future",$past_or_future)
                ->where("reki_no",$value->reki_no)
                ->delete();

                DB::table("mitumoriSaiRekis")
                ->where("reki_url",$url)
                ->where("past_or_future",$past_or_future)
                ->where("reki_no",$value->reki_no)
                ->delete();

                DB::table("mitumoriRekis")
                ->where("reki_url",$url)
                ->where("past_or_future",$past_or_future)
                ->where("reki_no",$value->reki_no)
                ->delete();
            }else{
                break;
            }
            
        }       
    }


    //項目直近で同じだった場合は消す
    public function MatchKaniDelete($past_or_future,$url=null){

        if($url==null) $_SERVER['HTTP_REFERER'];

        
        $reki=DB::table("mitumoriRekis")
        ->where("reki_url",$url)
        ->where("past_or_future",$past_or_future)
        ->where("kakutei_flg",1)
        ->orderBy("reki_no","desc")
        ->get();

        //データがないなら処理しない
        if(!isset($reki[0])) return;

        $m=DB::table("mitumoris")
        ->where("id",$reki[0]->id)
        ->select("kansetu_auto_calculate","rom_tanka","rom_tankaDe","rom_tankaTo","rom_gaku")
        ->first();

        $sais=DB::table("mitumoriSais")
        ->where("mitumoriId",$reki[0]->id)
        ->orderBy('id')
        ->get()->map(function ($row) {
            unset($row->created_at, $row->updated_at);
            return $row;
        });

        foreach ($reki as $key => $value) {
            $rekiSais=DB::table("mitumoriSaiRekis")
            ->select(DB::getSchemaBuilder()->getColumnListing("mitumoriSais"))
            ->where("reki_url",$url)
            ->where("past_or_future",$past_or_future)
            ->where("reki_no",$value->reki_no)
            ->orderBy('id')
            ->get()->map(function ($row) {
                unset($row->created_at, $row->updated_at);
                return $row;
            });

            //現在と比べるための見積もり
            $matchReki=DB::table("mitumoriRekis")
            ->select("kansetu_auto_calculate","rom_tanka","rom_tankaDe","rom_tankaTo","rom_gaku")
            ->where("reki_url",$url)->where("past_or_future",$past_or_future)
            ->where("reki_no",$value->reki_no)->first();

            if($rekiSais == $sais && $matchReki == $m){
                
                DB::table("mitumoriSaiRekis")
                ->where("reki_url",$url)
                ->where("past_or_future",$past_or_future)
                ->where("reki_no",$value->reki_no)
                ->delete();

                DB::table("mitumoriRekis")
                ->where("reki_url",$url)
                ->where("past_or_future",$past_or_future)
                ->where("reki_no",$value->reki_no)
                ->delete();
            }else{
                break;
            }
            
        }       
    }





}