<?php
 
namespace App\Services;
use Illuminate\Support\Facades\DB;

use carbon\Carbon;
use App\Models\Mitumori;

class KansetuhiService
{

//再計算
  public function kani($mitumoriId)
  {
    //一つ抽出
    $kansetu=DB::table("kansetu_calculations")
      ->where("mitumoriId", $mitumoriId)
      ->first();

    $kansetus=DB::table("kansetu_calculations")
      ->where("mitumoriId", $mitumoriId)
      ->get();

    $kansetu_auto_calculate=DB::table("mitumoris")
        ->where("mitumoris.id",$mitumoriId)
        ->first()->kansetu_auto_calculate;

    //見積ごとの間接費計算データがないと計算しなくいい　or
    //自動計算offの時もしなくていい
    if(is_null($kansetu)||$kansetu_auto_calculate==0){
      DB::table("mitumoris")->where("id", $mitumoriId)->update(["kansetu_update_flg"=>0]);
      return;
    }

    $msKnsetus=DB::table("mitumoriSais")
      ->where("mitumoriId", $mitumoriId)
      ->where("hiyo_kbn", 3)
      ->get();
    
    //再計算するため古い間接分コードが違うものは消す
    //同じものはupdateOrInsertで更新
    DB::table("mitumoriSais")
      ->where("mitumoriId", $mitumoriId)
      ->where("kansetu_bun_code",'!=',$kansetu->kansetu_bun_code)
      ->where("hiyo_kbn", 3)
      ->delete();
    //========================================-

    $m=DB::table("mitumoris")
    ->where('id', $mitumoriId)
    ->first();

    //先に消耗品雑材をいれる
    $samGaku=DB::table("mitumoriSais")
      ->where("mitumoriId", $mitumoriId)
      ->where("hiyo_kbn", 0)
      ->where("zai_kbn","like","_B%")//B資材
      ->selectRaw("sum(mitumoriSais.tanka*mitumoriSais.su) as gaku")
      ->get()->sum("gaku");

    $zatu=round(round($samGaku)*0.03);
    if($zatu>2147483647) $zatu=0;

    //消耗品雑材
    DB::table("mitumoriSais")
        ->updateOrInsert( 
          //明細区分 0:通常 1:消耗品雑材 2:経費 3:間接費
          ["hiyo_kbn"=>1,"mitumoriId"=>$mitumoriId],
          [
          "name"=>"消耗品雑材",
          "auto_tanka"=>$zatu,
          "tanka"=>$zatu,
          "su"=>1,
          "tani"=>"式",
          "created_at"=>Carbon::now(),
          "updated_at"=>Carbon::now(),
        ]);
    
      //間接費登録するときすべてのデータに同じものを入れるカラム
      $insertArray=[
                //明細区分 0:通常 1:消耗品雑材 2:経費 3:間接費
                "hiyo_kbn"=>3,
                'gyoNo'=>null,
                'tani'=>"式",
                'su'=>1,
                "created_at"=>Carbon::now(),
                "updated_at"=>Carbon::now()
            ];
            
    $mitumori=Mitumori::withGakuNotKansetu($mitumoriId)->first();

    //安全衛生経費
    if(!is_null($kansetus->where("kansetu_code",6)->first())){
      $an=round($mitumori->romGakuForHotei*$kansetus->where("kansetu_code", 6)->first()->kansetu_rate??0.00);
    }else{
      $an=0;
    }
    

    //建設業退職金共済掛金
    if(!is_null($kansetus->where("kansetu_code", 7)->first())){
      $bugakariForKen=$mitumori->bugakariForKen;
      $ken=round($bugakariForKen*$kansetus->where("kansetu_code", 7)->first()->kansetu_rate??0.00);
    }else{
      $ken=0;
    }
    

    if($kansetu->kansetu_bun_code==510){

      //直接工事費=====================================
      $tyokusetu=$mitumori->gaku;//資材労務費額
      //法定福利費　　労務費だけ×法定福利費率
      $hotei=round($mitumori->romGakuForHotei*(!is_null($kansetus->where("kansetu_code", 5)->first())?$kansetus->where("kansetu_code", 5)->first()->kansetu_rate:0.00));
      
      //直接費＋法定福利費
      $tyokusetu+=$hotei;
      //直接費＋建設行退職金共済掛金
      $tyokusetu+=$ken;
      //===============================================

      //共通仮設費
      $kyotu=$tyokusetu*$kansetus->where("kansetu_code", 10)->first()->kansetu_rate??0.00;
      if($kyotu>2147483647) $kyotu=0;

      //共通仮設費と安全衛生経費は表記は別々だが、計算時は足す
      $kyotuPlusAn=$kyotu+$an;
      
      //現場管理費
      $genba=($tyokusetu+$kyotuPlusAn)*$kansetus->where("kansetu_code", 20)->first()->kansetu_rate??0.00;
      if($genba>2147483647) $genba=0;

      //一般管理費
      $ipan=($tyokusetu+$kyotuPlusAn+$genba)*$kansetus->where("kansetu_code", 30)->first()->kansetu_rate??0.00;
      if($ipan>2147483647) $ipan=0;

      //諸経費
      $syokeihi=($tyokusetu+$kyotuPlusAn+$genba+$ipan)*$kansetus->where("kansetu_code", 40)->first()->kansetu_rate??0.00;
      if($syokeihi>2147483647) $syokeihi=0;

      // dd($tyokusetu,$kyotuPlusAn,$genba,$ipan);

      //法定福利費
      DB::table("mitumoriSais")
      ->updateOrInsert(
        ["kansetu_code"=>'5',"kansetu_bun_code"=>$kansetu->kansetu_bun_code,'mitumoriId'=>$mitumoriId],
        array_merge($insertArray,["name"=>"法定福利費","auto_tanka"=>$hotei,"tanka"=>$hotei]
        )
      );

      //安全衛生経費
      DB::table("mitumoriSais")
      ->updateOrInsert(
        ["kansetu_code"=>'6',"kansetu_bun_code"=>$kansetu->kansetu_bun_code,'mitumoriId'=>$mitumoriId],
        array_merge($insertArray,["name"=>"安全衛生経費","auto_tanka"=>$an,"tanka"=>$an]
        )
      );

      //建設業退職金共済掛金
      DB::table("mitumoriSais")
      ->updateOrInsert(
        ["kansetu_code"=>'7',"kansetu_bun_code"=>$kansetu->kansetu_bun_code,'mitumoriId'=>$mitumoriId],
        array_merge($insertArray,["name"=>"建設業退職金共済掛金","auto_tanka"=>$ken,"tanka"=>$ken]
        )
      );

      //共通仮設費
      DB::table("mitumoriSais")
      ->updateOrInsert(
        ["kansetu_code"=>'10',"kansetu_bun_code"=>$kansetu->kansetu_bun_code,'mitumoriId'=>$mitumoriId],
        array_merge($insertArray,["name"=>"共通仮設費","auto_tanka"=>$kyotu,"tanka"=>$kyotu]
        )
      );
      
      //現場管理費
      DB::table("mitumoriSais")
      ->updateOrInsert(
        ["kansetu_code"=>'20',"kansetu_bun_code"=>$kansetu->kansetu_bun_code,'mitumoriId'=>$mitumoriId],
        array_merge($insertArray,["name"=>"現場管理費","auto_tanka"=>$genba,"tanka"=>$genba]
        )
      );
     
      //一般管理費
      DB::table("mitumoriSais")
      ->updateOrInsert(
        ["kansetu_code"=>'30',"kansetu_bun_code"=>$kansetu->kansetu_bun_code,'mitumoriId'=>$mitumoriId],
        array_merge($insertArray,["name"=>"一般管理費","auto_tanka"=>$ipan,"tanka"=>$ipan])
      );
      
      //諸経費
      DB::table("mitumoriSais")
      ->updateOrInsert(
        ["kansetu_code"=>'40',"kansetu_bun_code"=>$kansetu->kansetu_bun_code,'mitumoriId'=>$mitumoriId],
        array_merge($insertArray,["name"=>"諸経費","auto_tanka"=>$syokeihi,"tanka"=>$syokeihi])
      );
      
    }elseif($kansetu->kansetu_bun_code==520){


    //複合用新設  
    }elseif($kansetu->kansetu_bun_code==530){
          
          
      //直接工事費=====================================
      $tyokusetu=$mitumori->gaku;//資材労務費額
      //法定福利費　　労務費だけ×法定福利費率
      $hotei=round($mitumori->romGakuForHotei*(!is_null($kansetus->where("kansetu_code", 5)->first())?$kansetus->where("kansetu_code", 5)->first()->kansetu_rate:0.00));
      
      //直接費＋法定福利費
      $tyokusetu+=$hotei;
      //直接費＋建設行退職金共済掛金
      $tyokusetu+=$ken;
      //===============================================


      //共通仮設費率
      if($tyokusetu / 1000<= 3000){
        $kyotuksetuhiritu=4.03;
      }elseif($tyokusetu / 1000<=3000000){
        $kyotuksetuhiritu=ROUND(5.02*(($tyokusetu/1000) ** (-0.0273) ),2);
      }else{
        $kyotuksetuhiritu=3.34;
      }

      //共通仮設費
      $kyotu=$tyokusetu*$kyotuksetuhiritu/100;

      //共通仮設費の中に安全衛生経費が含まれているが別で導き出しているため
      //二重形状にならないように共通仮設費から安全衛生経費分を引く
      //共通仮設費のほうが安全衛生経費より大きい時
      if($kyotu>$an){
        $kyotu=$kyotu-$an;
      }else{
        //引いたらマイナスになるため０にする
        $kyotu=0;
      }

      //純工事費
      $junkozi=$kyotu+$tyokusetu+$an;

      //現場管理費率
      if($junkozi / 1000<= 3000){
        $genbakanrihiritu=21.24;
      }elseif($junkozi / 1000<=3000000){
        $genbakanrihiritu=ROUND(67.75*(($junkozi/1000) ** (-0.1449) ),2);
      }else{
        $genbakanrihiritu=7.81;
      }

      //現場管理費
      $genba=$junkozi*$genbakanrihiritu/100;

      //<工事原価>
      $kozigenka=$junkozi+$genba;

      //一般管理費率
      if($kozigenka / 1000<= 3000){
        $ippankanrihiritu=11.8;
      }elseif($kozigenka / 1000<=2000000){
        $ippankanrihiritu=ROUND(17.286-(1.577*LOG($kozigenka/1000) ),2);
      }else{
        $ippankanrihiritu=7.35;
      }

      //一般管理費
      $ipan=$kozigenka*$ippankanrihiritu/100;
      
      //法定福利費
      DB::table("mitumoriSais")
      ->updateOrInsert(
        ["kansetu_code"=>'5',"kansetu_bun_code"=>$kansetu->kansetu_bun_code,'mitumoriId'=>$mitumoriId],
        array_merge($insertArray,["name"=>"法定福利費","auto_tanka"=>$hotei,"tanka"=>$hotei]
        )
      );

      //安全衛生経費
      DB::table("mitumoriSais")
      ->updateOrInsert(
        ["kansetu_code"=>'6',"kansetu_bun_code"=>$kansetu->kansetu_bun_code,'mitumoriId'=>$mitumoriId],
        array_merge($insertArray,["name"=>"安全衛生経費","auto_tanka"=>$an,"tanka"=>$an]
        )
      );

      //建設業退職金共済掛金
      DB::table("mitumoriSais")
      ->updateOrInsert(
        ["kansetu_code"=>'7',"kansetu_bun_code"=>$kansetu->kansetu_bun_code,'mitumoriId'=>$mitumoriId],
        array_merge($insertArray,["name"=>"建設業退職金共済掛金","auto_tanka"=>$ken,"tanka"=>$ken]
        )
      );

      //共通仮設費
      DB::table("mitumoriSais")
      ->updateOrInsert(
        ["kansetu_code"=>20,"kansetu_bun_code"=>$kansetu->kansetu_bun_code,'mitumoriId'=>$mitumoriId],
        array_merge($insertArray,["name"=>"共通仮設費","auto_tanka"=>$kyotu,"tanka"=>$kyotu]
        )
      );
      
      //現場管理費
      DB::table("mitumoriSais")
      ->updateOrInsert(
        ["kansetu_code"=>50,"kansetu_bun_code"=>$kansetu->kansetu_bun_code,'mitumoriId'=>$mitumoriId],
        array_merge($insertArray,["name"=>"現場管理費","auto_tanka"=>$genba,"tanka"=>$genba]
        )
      );
     
      //一般管理費
      DB::table("mitumoriSais")
      ->updateOrInsert(
        ["kansetu_code"=>80,"kansetu_bun_code"=>$kansetu->kansetu_bun_code,'mitumoriId'=>$mitumoriId],
        array_merge($insertArray,["name"=>"一般管理費","auto_tanka"=>$ipan,"tanka"=>$ipan])
      );

    }elseif($kansetu->kansetu_bun_code==540){

      //直接工事費=====================================
      $tyokusetu=$mitumori->gaku;//資材労務費額
      //法定福利費　　労務費だけ×法定福利費率
      $hotei=round($mitumori->romGakuForHotei*(!is_null($kansetus->where("kansetu_code", 5)->first())?$kansetus->where("kansetu_code", 5)->first()->kansetu_rate:0.00));
      //直接費＋法定福利費
      $tyokusetu+=$hotei;
      //直接費＋建設業退職共済掛金
      $tyokusetu+=$ken;
      //===============================================


      //共通仮設費率
      if($tyokusetu / 1000<= 3000){
        $kyotuksetuhiritu=3.88;
      }elseif($tyokusetu / 1000<=3000000){
        $kyotuksetuhiritu=ROUND(11.93*(($tyokusetu/1000) ** (-0.1404) ),2);
      }else{
        $kyotuksetuhiritu=2.03;
      }

      //共通仮設費
      $kyotu=$tyokusetu*$kyotuksetuhiritu/100;

      //共通仮設費の中に安全衛生経費が含まれているが別で導き出しているため
      //二重形状にならないように共通仮設費から安全衛生経費分を引く
      //共通仮設費のほうが安全衛生経費より大きい時
      if($kyotu>$an){
        $kyotu=$kyotu-$an;
      }else{
        //引いたらマイナスになるため０にする
        $kyotu=0;
      }

      //純工事費
      $junkozi=$kyotu+$tyokusetu+$an;

      //現場管理費率
      if($junkozi / 1000<= 3000){
        $genbakanrihiritu=20.37;
      }elseif($junkozi / 1000<=3000000){
        $genbakanrihiritu=ROUND(117.91*(($junkozi/1000) ** (-0.2193) ),2);
      }else{
        $genbakanrihiritu=7.42;
      }

      //現場管理費
      $genba=$junkozi*$genbakanrihiritu/100;

      //<工事原価>
      $kozigenka=$junkozi+$genba;

      //一般管理費率
      if($kozigenka / 1000<= 3000){
        $ippankanrihiritu=11.8;
      }elseif($kozigenka / 1000<=2000000){
        $ippankanrihiritu=ROUND(17.286-(1.577*LOG($kozigenka/1000) ),2);
      }else{
        $ippankanrihiritu=7.35;
      }

      //一般管理費
      $ipan=$kozigenka*$ippankanrihiritu/100;  

      //法定福利費
      DB::table("mitumoriSais")
      ->updateOrInsert(
        ["kansetu_code"=>'5',"kansetu_bun_code"=>$kansetu->kansetu_bun_code,'mitumoriId'=>$mitumoriId],
        array_merge($insertArray,["name"=>"法定福利費","auto_tanka"=>$hotei,"tanka"=>$hotei]
        )
      );

      //安全衛生経費
      DB::table("mitumoriSais")
      ->updateOrInsert(
        ["kansetu_code"=>'6',"kansetu_bun_code"=>$kansetu->kansetu_bun_code,'mitumoriId'=>$mitumoriId],
        array_merge($insertArray,["name"=>"安全衛生経費","auto_tanka"=>$an,"tanka"=>$an]
        )
      );

      //建設業退職金共済掛金
      DB::table("mitumoriSais")
      ->updateOrInsert(
        ["kansetu_code"=>'7',"kansetu_bun_code"=>$kansetu->kansetu_bun_code,'mitumoriId'=>$mitumoriId],
        array_merge($insertArray,["name"=>"建設業退職金共済掛金","auto_tanka"=>$ken,"tanka"=>$ken]
        )
      );

      //共通仮設費
      DB::table("mitumoriSais")
      ->updateOrInsert(
        ["kansetu_code"=>20,"kansetu_bun_code"=>$kansetu->kansetu_bun_code,'mitumoriId'=>$mitumoriId],
        array_merge($insertArray,["name"=>"共通仮設費","auto_tanka"=>$kyotu,"tanka"=>$kyotu]
        )
      );
      
      //現場管理費
      DB::table("mitumoriSais")
      ->updateOrInsert(
        ["kansetu_code"=>50,"kansetu_bun_code"=>$kansetu->kansetu_bun_code,'mitumoriId'=>$mitumoriId],
        array_merge($insertArray,["name"=>"現場管理費","auto_tanka"=>$genba,"tanka"=>$genba]
        )
      );
     
      //一般管理費
      DB::table("mitumoriSais")
      ->updateOrInsert(
        ["kansetu_code"=>80,"kansetu_bun_code"=>$kansetu->kansetu_bun_code,'mitumoriId'=>$mitumoriId],
        array_merge($insertArray,["name"=>"一般管理費","auto_tanka"=>$ipan,"tanka"=>$ipan])
      );

      //値がないものは削除
      // DB::table("mitumoriSais")->where("gaku",0)->where("kansetu_flg", true)->where("mitumoriId",$mitumoriId)->delete();


    }elseif($kansetu->kansetu_bun_code==630){

    }elseif($kansetu->kansetu_bun_code==640){

    }

    DB::table("mitumoris")
      ->where("id", $mitumoriId)
      ->update(["kansetu_update_flg"=>0]);
  }
  
//階層
  public function kaiso($mitumoriId)
  {
    //一つ抽出
    $kansetu=DB::table("kansetu_calculations")
      ->where("mitumoriId", $mitumoriId)
      ->first();

    $kansetus=DB::table("kansetu_calculations")
      ->where("mitumoriId", $mitumoriId)
      ->get();

    $kansetu_auto_calculate=DB::table("mitumoris")
        ->where("mitumoris.id",$mitumoriId)
        ->first()->kansetu_auto_calculate;

    //見積ごとの間接費計算データがないと計算しなくいい　or
    //自動計算offの時もしなくていい
    if(is_null($kansetu)||$kansetu_auto_calculate==0){
      DB::table("mitumoris")->where("id", $mitumoriId)->update(["kansetu_update_flg"=>0]);
      return;
    }

    //再計算するため古いのは消す
    DB::table("mitumoriKos")
      ->where("mitumoriId", $mitumoriId)
      //明細区分 0:通常 1:消耗品雑材 2:経費 3:間接費
      ->where("hiyo_kbn", 3)
      ->where("kansetu_bun_code","!=",$kansetu->kansetu_bun_code)
      ->delete();

    $insertArray=[
          //明細区分 0:通常 1:消耗品雑材 2:経費 3:間接費
          "hiyo_kbn"=>3,'gyoNo'=>null,'tani'=>"式",'su'=>1,
          "created_at"=>Carbon::now(),
          "updated_at"=>Carbon::now()
      ];

    
    $mitumori=Mitumori::withGakuNotKansetu($mitumoriId)->first();

    //安全衛生経費
    if(!is_null($kansetus->where("kansetu_code", 6)->first())){
      $an=round($mitumori->romGakuForHotei*$kansetus->where("kansetu_code", 6)->first()->kansetu_rate??0.00);
    }else{
      $an=0;
    }
    
    //建設業退職金共済掛金
    if(!is_null($kansetus->where("kansetu_code", 7)->first())){
      $bugakariForKen=$mitumori->bugakariForKen;
      $ken=round($bugakariForKen*$kansetus->where("kansetu_code", 7)->first()->kansetu_rate??0.00);
    }else{
      $ken=0;
    }
      
    if($kansetu->kansetu_bun_code==510){
    
      //==================================-
      //直接工事費
      $tyokusetu=$mitumori->gaku;//資材労務費額

      //法定福利費　　労務費だけ×法定福利費率
      $hotei=round($mitumori->romGakuForHotei*(!is_null($kansetus->where("kansetu_code", 5)->first())?$kansetus->where("kansetu_code", 5)->first()->kansetu_rate:0.00));
      
      //直接費＋法定福利費
      $tyokusetu+=$hotei;
      //直接費＋建設行退職金共済掛金
      $tyokusetu+=$ken;
      //==================================
      
      
      //共通仮設費
      $kyotu=$tyokusetu*$kansetus->where("kansetu_code", 10)->first()->kansetu_rate??0.00;
      if($kyotu>2147483647) $kyotu=0;

      //共通仮設費と安全衛生経費は表記は別々だが、計算時は足す
      $kyotuPlusAn=$kyotu+$an;

      //現場管理費
      $genba=($tyokusetu+$kyotuPlusAn)*$kansetus->where("kansetu_code", 20)->first()->kansetu_rate??0.00;
      if($genba>2147483647) $genba=0;

      //一般管理費
      $ipan=($tyokusetu+$kyotuPlusAn+$genba)*$kansetus->where("kansetu_code", 30)->first()->kansetu_rate??0.00;
      if($ipan>2147483647) $ipan=0;
      
      //諸経費    
      $syokeihi=($tyokusetu+$kyotuPlusAn+$genba+$ipan)*$kansetus->where("kansetu_code", 40)->first()->kansetu_rate??0.00;
      if($syokeihi>2147483647) $syokeihi=0;
      
      //法定福利費
      DB::table("mitumoriKos")
      ->updateOrInsert(
        ["kansetu_code"=>'5',"kansetu_bun_code"=>$kansetu->kansetu_bun_code,'mitumoriId'=>$mitumoriId],
        array_merge($insertArray,["name"=>"法定福利費","auto_tanka"=>$hotei,"tanka"=>$hotei])
      );

      //安全衛生経費
      DB::table("mitumoriKos")
      ->updateOrInsert(
        ["kansetu_code"=>'6',"kansetu_bun_code"=>$kansetu->kansetu_bun_code,'mitumoriId'=>$mitumoriId],
        array_merge($insertArray,["name"=>"安全衛生経費","auto_tanka"=>$an,"tanka"=>$an]
        )
      );

      //建設業退職金共済掛金
      DB::table("mitumoriKos")
      ->updateOrInsert(
        ["kansetu_code"=>'7',"kansetu_bun_code"=>$kansetu->kansetu_bun_code,'mitumoriId'=>$mitumoriId],
        array_merge($insertArray,["name"=>"建設業退職金共済掛金","auto_tanka"=>$ken,"tanka"=>$ken]
        )
      );
      
      //共通仮設費
      DB::table("mitumoriKos")
      ->updateOrInsert(
        ["kansetu_code"=>'10',"kansetu_bun_code"=>$kansetu->kansetu_bun_code,'mitumoriId'=>$mitumoriId],
        array_merge($insertArray,["name"=>"共通仮設費","auto_tanka"=>$kyotu,"tanka"=>$kyotu])
      );
      
      //現場管理費
      DB::table("mitumoriKos")
      ->updateOrInsert(
        ["kansetu_code"=>'20',"kansetu_bun_code"=>$kansetu->kansetu_bun_code,'mitumoriId'=>$mitumoriId],
        array_merge($insertArray,["name"=>"現場管理費","auto_tanka"=>$genba,"tanka"=>$genba])
      );
     
      //一般管理費
      DB::table("mitumoriKos")
      ->updateOrInsert(
        ["kansetu_code"=>'30',"kansetu_bun_code"=>$kansetu->kansetu_bun_code,'mitumoriId'=>$mitumoriId],
        array_merge($insertArray,["name"=>"一般管理費","auto_tanka"=>$ipan,"tanka"=>$ipan])
      );
      
      //諸経費
      DB::table("mitumoriKos")
      ->updateOrInsert(
        ["kansetu_code"=>'40',"kansetu_bun_code"=>$kansetu->kansetu_bun_code,'mitumoriId'=>$mitumoriId],
        array_merge($insertArray,["name"=>"諸経費","auto_tanka"=>$syokeihi,"tanka"=>$syokeihi])
      );

      //値がないものは削除
      // DB::table("mitumoriKos")->where("gaku",0)->where("kansetu_flg", true)->where("mitumoriId",$mitumoriId)->delete();
    
      
    
    }elseif($kansetu->kansetu_bun_code==520){

    }elseif($kansetu->kansetu_bun_code==530){
      
      //==================================-
      //直接工事費
      $tyokusetu=$mitumori->gaku;//資材労務費額
      //法定福利費　　労務費だけ×法定福利費率
      $hotei=round($mitumori->romGakuForHotei*(!is_null($kansetus->where("kansetu_code", 5)->first())?$kansetus->where("kansetu_code", 5)->first()->kansetu_rate:0.00));
      //直接費＋法定福利費
      $tyokusetu+=$hotei;
      //直接費＋建設行退職金共済掛金
      $tyokusetu+=$ken;
      //==================================



      //共通仮設費率
      if($tyokusetu / 1000<= 3000){
        $kyotuksetuhiritu=4.03;
      }elseif($tyokusetu / 1000<=3000000){
        $kyotuksetuhiritu=ROUND(5.02*(($tyokusetu/1000) ** (-0.0273) ),2);
      }else{
        $kyotuksetuhiritu=3.34;
      }

      //共通仮設費
      $kyotu=$tyokusetu*$kyotuksetuhiritu/100;

      //共通仮設費の中に安全衛生経費が含まれているが別で導き出しているため
      //二重形状にならないように共通仮設費から安全衛生経費分を引く
      //共通仮設費のほうが安全衛生経費より大きい時
      if($kyotu>$an){
        $kyotu=$kyotu-$an;
      }else{
        //引いたらマイナスになるため０にする
        $kyotu=0;
      }

      //純工事費
      $junkozi=$kyotu+$tyokusetu+$an;

      //現場管理費率
      if($junkozi / 1000<= 3000){
        $genbakanrihiritu=21.24;
      }elseif($junkozi / 1000<=3000000){
        $genbakanrihiritu=ROUND(67.75*(($junkozi/1000) ** (-0.1449) ),2);
      }else{
        $genbakanrihiritu=7.81;
      }

      //現場管理費
      $genba=$junkozi*$genbakanrihiritu/100;

      //<工事原価>
      $kozigenka=$junkozi+$genba;

      //一般管理費率
      if($kozigenka / 1000<= 3000){
        $ippankanrihiritu=11.8;
      }elseif($kozigenka / 1000<=2000000){
        $ippankanrihiritu=ROUND(17.286-(1.577*LOG($kozigenka/1000) ),2);
      }else{
        $ippankanrihiritu=7.35;
      }

      //一般管理費
      $ipan=$kozigenka*$ippankanrihiritu/100;

      

      //法定福利費
      DB::table("mitumoriKos")
      ->updateOrInsert(
        ["kansetu_code"=>'5',"kansetu_bun_code"=>$kansetu->kansetu_bun_code,'mitumoriId'=>$mitumoriId],
        array_merge($insertArray,["name"=>"法定福利費","auto_tanka"=>$hotei,"tanka"=>$hotei])
      );

      //安全衛生経費
      DB::table("mitumoriKos")
      ->updateOrInsert(
        ["kansetu_code"=>'6',"kansetu_bun_code"=>$kansetu->kansetu_bun_code,'mitumoriId'=>$mitumoriId],
        array_merge($insertArray,["name"=>"安全衛生経費","auto_tanka"=>$an,"tanka"=>$an]
        )
      );

      //建設業退職金共済掛金
      DB::table("mitumoriKos")
      ->updateOrInsert(
        ["kansetu_code"=>'7',"kansetu_bun_code"=>$kansetu->kansetu_bun_code,'mitumoriId'=>$mitumoriId],
        array_merge($insertArray,["name"=>"建設業退職金共済掛金","auto_tanka"=>$ken,"tanka"=>$ken]
        )
      );


      //共通仮設費
      DB::table("mitumoriKos")
      ->updateOrInsert(
        ["kansetu_code"=>'20',"kansetu_bun_code"=>$kansetu->kansetu_bun_code,'mitumoriId'=>$mitumoriId],
        array_merge($insertArray,["name"=>"共通仮設費","auto_tanka"=>$kyotu,"tanka"=>$kyotu])
      );
      
      //現場管理費
      DB::table("mitumoriKos")
      ->updateOrInsert(
        ["kansetu_code"=>'50',"kansetu_bun_code"=>$kansetu->kansetu_bun_code,'mitumoriId'=>$mitumoriId],
        array_merge($insertArray,["name"=>"現場管理費","auto_tanka"=>$genba,"tanka"=>$genba])
      );
     
      //一般管理費
      DB::table("mitumoriKos")
      ->updateOrInsert(
        ["kansetu_code"=>'80',"kansetu_bun_code"=>$kansetu->kansetu_bun_code,'mitumoriId'=>$mitumoriId],
        array_merge($insertArray,["name"=>"一般管理費","auto_tanka"=>$ipan,"tanka"=>$ipan])
      );

      //値がないものは削除
      // DB::table("mitumoriKos")->where("gaku",0)->where("kansetu_flg", true)->where("mitumoriId",$mitumoriId)->delete();

    }elseif($kansetu->kansetu_bun_code??null==540){

      
      //==================================-
      //直接工事費
      $tyokusetu=$mitumori->gaku;//資材労務費額
      //法定福利費　　労務費だけ×法定福利費率
      $hotei=round($mitumori->romGakuForHotei*(!is_null($kansetus->where("kansetu_code", 5)->first())?$kansetus->where("kansetu_code", 5)->first()->kansetu_rate:0.00));
      //直接費＋法定福利費
      $tyokusetu+=$hotei;
      //直接費＋建設行退職金共済掛金
      $tyokusetu+=$ken;
      //==================================

      //共通仮設費率
      if($tyokusetu / 1000<= 3000){
        $kyotuksetuhiritu=3.88;
      }elseif($tyokusetu / 1000<=3000000){
        $kyotuksetuhiritu=ROUND(11.93*(($tyokusetu/1000) ** (-0.1404) ),2);
      }else{
        $kyotuksetuhiritu=2.03;
      }

      //共通仮設費
      $kyotu=$tyokusetu*$kyotuksetuhiritu/100;

      //共通仮設費の中に安全衛生経費が含まれているが別で導き出しているため
      //二重形状にならないように共通仮設費から安全衛生経費分を引く
      //共通仮設費のほうが安全衛生経費より大きい時
      if($kyotu>$an){
        $kyotu=$kyotu-$an;
      }else{
        //引いたらマイナスになるため０にする
        $kyotu=0;
      }

      //純工事費
      $junkozi=$kyotu+$tyokusetu+$an;

      //現場管理費率
      if($junkozi / 1000<= 3000){
        $genbakanrihiritu=20.37;
      }elseif($junkozi / 1000<=3000000){
        $genbakanrihiritu=ROUND(117.91*(($junkozi/1000) ** (-0.2193) ),2);
      }else{
        $genbakanrihiritu=7.42;
      }

      //現場管理費
      $genba=$junkozi*$genbakanrihiritu/100;

      //<工事原価>
      $kozigenka=$junkozi+$genba;

      //一般管理費率
      if($kozigenka / 1000<= 3000){
        $ippankanrihiritu=11.8;
      }elseif($kozigenka / 1000<=2000000){
        $ippankanrihiritu=ROUND(17.286-(1.577*LOG($kozigenka/1000) ),2);
      }else{
        $ippankanrihiritu=7.35;
      }

      //一般管理費
      $ipan=$kozigenka*$ippankanrihiritu/100;

      //法定福利費
      DB::table("mitumoriKos")
      ->updateOrInsert(
        ["kansetu_code"=>'5',"kansetu_bun_code"=>$kansetu->kansetu_bun_code,'mitumoriId'=>$mitumoriId],
        array_merge($insertArray,["name"=>"法定福利費","auto_tanka"=>$hotei,"tanka"=>$hotei])
      );

      //安全衛生経費
      DB::table("mitumoriKos")
      ->updateOrInsert(
        ["kansetu_code"=>'6',"kansetu_bun_code"=>$kansetu->kansetu_bun_code,'mitumoriId'=>$mitumoriId],
        array_merge($insertArray,["name"=>"安全衛生経費","auto_tanka"=>$an,"tanka"=>$an]
        )
      );

      //建設業退職金共済掛金
      DB::table("mitumoriKos")
      ->updateOrInsert(
        ["kansetu_code"=>'7',"kansetu_bun_code"=>$kansetu->kansetu_bun_code,'mitumoriId'=>$mitumoriId],
        array_merge($insertArray,["name"=>"建設業退職金共済掛金","auto_tanka"=>$ken,"tanka"=>$ken]
        )
      );

      //共通仮設費
      DB::table("mitumoriKos")
      ->updateOrInsert(
        ["kansetu_code"=>'20',"kansetu_bun_code"=>$kansetu->kansetu_bun_code,'mitumoriId'=>$mitumoriId],
        array_merge($insertArray,["name"=>"共通仮設費","auto_tanka"=>$kyotu,"tanka"=>$kyotu])
      );
      
      //現場管理費
      DB::table("mitumoriKos")
      ->updateOrInsert(
        ["kansetu_code"=>'50',"kansetu_bun_code"=>$kansetu->kansetu_bun_code,'mitumoriId'=>$mitumoriId],
        array_merge($insertArray,["name"=>"現場管理費","auto_tanka"=>$genba,"tanka"=>$genba])
      );
     
      //一般管理費
      DB::table("mitumoriKos")
      ->updateOrInsert(
        ["kansetu_code"=>'80',"kansetu_bun_code"=>$kansetu->kansetu_bun_code,'mitumoriId'=>$mitumoriId],
        array_merge($insertArray,["name"=>"一般管理費","auto_tanka"=>$ipan,"tanka"=>$ipan])
      );

    }elseif($kansetu->kansetu_bun_code==630){

    }elseif($kansetu->kansetu_bun_code==640){

    }
    DB::table("mitumoris")
      ->where("id", $mitumoriId)
      ->update(["kansetu_update_flg"=>0]);
      
  }
}