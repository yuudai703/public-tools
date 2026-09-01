<?php
 
namespace App\Services;
use Illuminate\Support\Facades\DB;
use carbon\Carbon;
use App\Models\MitumoriKo;

class SetubiZatuzaiService
{

//再計算
  public function kani($mitumoriId)
  {
    
    $m=DB::table("mitumoriSetubis")
    ->where('id', $mitumoriId)
    ->first();

    //自動計算offならないもしない
    if($m->kansetu_auto_calculate == 0) return;

    //見積
    $samGaku=DB::table("mitumoriSetubiSais")
      ->where("mitumoriId", $mitumoriId)
      ->where("hiyo_kbn", 0)
      ->where("zai_kbn","like","_B%");//B資材

    $samGaku->selectRaw("sum(tanka*su) as gaku");

    $samGaku=$samGaku->get()->sum("gaku");
      
    //消耗品雑材
    DB::table("mitumoriSetubiSais")
      ->updateOrInsert(
        [
          "hiyo_kbn"=>1,
          "mitumoriId"=>$mitumoriId,
        ],
        [
        "name"=>"消耗品雑材",
        "gaku"=>round($samGaku*0.03),
        "auto_tanka"=>round($samGaku*0.03),
        "tanka"=>round($samGaku*0.03),
        "su"=>1,
        "tani"=>"式",
        "created_at"=>Carbon::now(),
        "updated_at"=>Carbon::now(),
      ]);
    
      
  }
  
//階層
  public function kaiso($mitumoriId)
  {

    //同じ見積りのものは一括で雑材を再計算
    //理由は、同じ見積り内の別の見積項目になる資材単価が連動している可能性があるから    
    $mitumoriKo_kansetu_auto_calculates=DB::table("mitumoriSetubiKos")
    ->where('mitumoriId', $mitumoriId)
    ->where('hiyo_kbn',0)
    ->where('kansetu_auto_calculate',1);
    
    $m=DB::table("mitumoriSetubis")
    ->where('id', $mitumoriId)
    ->first();

    //複合単価は単価の中に雑材が含まれているため、雑材計算は不要
      $mitumoriSetubiSais = DB::table("mitumoriSetubiSais")
             ->where("mitumoriSetubiSais.mitumoriId", $mitumoriId)
             ->where("mitumoriSetubiSais.hiyo_kbn", 0)
             ->where("zai_kbn","like","_B%")//B資材
             ->select(
                 "mitumoriKoId",
                 DB::raw("sum(
                          round(cast(
                            mitumoriSetubiSais.su*mitumoriSetubiSais.tanka
                          as decimal))
                        ) as gaku"),
             )
             ->groupBy("mitumoriKoId");

    $insertArray=DB::table(DB::raw("({$mitumoriKo_kansetu_auto_calculates->toSql()}) as mitumoriSetubiKos"))
      ->leftJoin(DB::raw("({$mitumoriSetubiSais->toSql()}) as mitumoriSetubiSais"), function ($join) {
                $join->on("mitumoriSetubiKos.id", "mitumoriSetubiSais.mitumoriKoId");
            })
            ->setBindings(array_merge($mitumoriKo_kansetu_auto_calculates->getBindings(),$mitumoriSetubiSais->getBindings())) 
            ->select(
              DB::raw("'消耗品雑材' as name"),
              DB::raw("mitumoriSetubiKos.mitumoriId"),
              DB::raw("mitumoriSetubiKos.id as mitumoriKoId"),
              DB::raw("coalesce(mitumoriSetubiSais.gaku,0) as gaku"),
              DB::raw("coalesce(mitumoriSetubiSais.gaku,0) as auto_tanka"),
              DB::raw("coalesce(mitumoriSetubiSais.gaku,0) as tanka"),
              DB::raw("1 as su"),
              DB::raw("'式' as tani"),
              DB::raw("1 as hiyo_kbn"),
      )
      ->get()->toArray(); 

      $insertArray=json_decode(json_encode($insertArray),true);

      // 銀行丸目対策
      foreach($insertArray as &$v){
          $v["auto_tanka"]=round($v["auto_tanka"]*0.03);
          $v["tanka"]=round($v["tanka"]*0.03);
          DB::table("mitumoriSetubiSais")->updateOrInsert(
            ["hiyo_kbn"=>1,"mitumoriId"=>$v["mitumoriId"],"mitumoriKoId"=>$v["mitumoriKoId"]],$v
          );
      }
   
      }
}