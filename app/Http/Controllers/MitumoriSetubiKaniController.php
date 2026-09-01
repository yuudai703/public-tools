<?php
namespace App\Http\Controllers;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Date;
use App\Models\Mitumori as Mitumori;
use App\Models\MitumoriSai as MitumoriSai;
use App\Services\KansetuhiService;
use App\Services\RekiService;


use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

use Maatwebsite\Excel\Facades\Excel; 
use App\Exports\UsersExport; 
use App\Exports\Export;
use App\Models\User;

use PhpOffice\PhpSpreadsheet\Spreadsheet as Spread;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;


class MitumoriSetubiKaniController extends Controller
{
    protected $kansetuhiService;
    protected $RekiService;
    public function __construct(KansetuhiService $kansetuhiService,RekiService $RekiService)
    {
        $this->kansetuhiService = $kansetuhiService;
        $this->RekiService = $RekiService;
    }

    public function index($id, Request $request){

        //ゴミデータを消す
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http')
            . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        
        $this->RekiService->MatchKaniDelete('future',$url);
        $this->RekiService->MatchKaniDelete('past',$url);

        list($future,$past)=$this->RekiService->checkReki($url);
    
        // DB::table("logs")->insert([
        //     "user_id"=>Auth::id(),
        //     "mitumori_id"=>$id,
        //     "action"=>"簡易見積にアクセス"
        // ]);

        //選択した見積
        $mitumori=DB::table("mitumoris as mitumoris")->where("mitumoris.id",$id)
        ->join("folders",function($join){
            $join->on("mitumoris.groupId","=","folders.id");
        })
        ->select(
            "mitumoris.*",
            "folders.name as comName",
        )
        ->first();

        $romTankaDe=$mitumori->rom_tankaDe;
        $romTanka=$mitumori->rom_tanka;
        $romTankaTo=$mitumori->rom_tankaTo;

        if($mitumori->kansetu_auto_calculate==1){
            //間接費計算
            $this->kansetuhiService->kani($mitumori->id);
        }


        //並べ替えし直し    
        $mitumoriSais=MitumoriSai::where("mitumoriId",$id)
            ->orderBy("hiyo_kbn","asc")
            ->orderBy("gyoNo","asc")
            ->orderBy("sizaiCode","asc")
            ->orderBy(DB::raw("CAST(kansetu_code AS SIGNED)"),"asc")
            ->get();

            foreach($mitumoriSais as $key=>$mitumoriSai){
                $mitumoriSai->gyoNo=$key+1;
                $mitumoriSai->save();
            }


        $kansetus=DB::table("kansetu_buns")
                ->select("kansetu_buns.name","kansetu_buns.code")
                        ->join("kansetu_komokus","kansetu_komokus.bun_code","=","kansetu_buns.code")
                        ->where("kansetu_komokus.yoso_ysan","<>","")
                        //現在計算できる間接費はこれだけだから
                        ->whereIn("kansetu_buns.code",[510,530,540])
                        ->where("kansetu_buns.name","<>","電気設備内訳間接費")
                        ->where("kansetu_buns.name","<>","複合用 電気設備内訳間接費")
                        ->distinct()
                        ->orderBy("kansetu_buns.code")
                        ->get();
    
        $kansetuKoumokus=DB::table("kansetu_komokus")
                ->whereIn("kansetu_komokus.bun_code",[510,530,540])
                ->leftjoin("kansetu_calculations",function($join)use($id){
                    $join->on("kansetu_calculations.kansetu_code","=","kansetu_komokus.code")
                        ->on("kansetu_calculations.kansetu_bun_code","=","kansetu_komokus.bun_code")
                        ->where("kansetu_calculations.mitumoriId","=",$id);
                })
                ->select(
                    "kansetu_komokus.bun_code"
                    ,"kansetu_komokus.code"
                    ,"kansetu_komokus.name"
                    ,"kansetu_komokus.j_exp"
                    ,DB::raw("if(kansetu_calculations.kansetu_rate is null,kansetu_komokus.rate,kansetu_calculations.kansetu_rate) as rate")
                    ,DB::raw("if(kansetu_calculations.kansetu_rate is null,false,true) as selectedKansetu")
                )
                ->orderBy("kansetu_komokus.bun_code")
                ->orderBy(DB::raw("CAST(kansetu_komokus.code AS SIGNED)"),"asc")
                ->get();

                
        $selectedCode=$kansetuKoumokus->where("selectedKansetu",true)->first()->bun_code;
        

        $totalGaku=$mitumori->gaku;
        $kansetu_auto_calculate=$mitumori->kansetu_auto_calculate;
        $mitumoriSais = DB::table("mitumoriSais")
            ->where("mitumoriId",$id)
            ->select(
            "mitumoriSais.id",
            "mitumoriSais.name",
            "mitumoriSais.siyo",
            DB::raw("if(mitumoriSais.su!=0,mitumoriSais.su,'') as su"),
            "mitumoriSais.memo",
            "mitumoriSais.gyoText",

            DB::raw("
             case
              when hiyo_kbn = 1 and '$kansetu_auto_calculate'=1 then mitumoriSais.auto_tanka /*消耗品雑材*/
              when hiyo_kbn = 3 and '$kansetu_auto_calculate'=1 then mitumoriSais.auto_tanka /*間接費*/
              else mitumoriSais.tanka end 
            as tanka"),

            DB::raw("
            round(cast(
                case
                when hiyo_kbn in(1,3) and '$kansetu_auto_calculate'=1 then mitumoriSais.auto_tanka*su /*消耗品雑材 間接費*/
                else mitumoriSais.tanka*su end 
            as decimal))
            as gaku"),

            "mitumoriSais.biko",
            "mitumoriSais.gyoNo as gyoNoI",
            DB::raw("
                case
                    when hiyo_kbn=3 then '間接費'
                    when hiyo_kbn in (2,4) then '経費'
                    else mitumoriSais.gyoNo
                end
                 as gyoNo"),
            DB::raw("
                case
                    when hiyo_kbn=0 then 'sizaiTanka'
                    when hiyo_kbn=1 then 'zatuTankaCell'
                    when hiyo_kbn=2 then 'keihiTankaCell'
                    when hiyo_kbn=3 then 'kansetuTankaCell'
                    when hiyo_kbn=4 then 'bottomTankaCell'
                    when hiyo_kbn=5 then 'disTankaCell'
                end
                 as tClassName"),
            DB::raw("
                case
                    when hiyo_kbn=0 then 'sizaiHTanka'
                    when hiyo_kbn=1 then 'zatuHTankaCell'
                    when hiyo_kbn=2 then 'keihiHTankaCell'
                    when hiyo_kbn=3 then 'kansetuHTankaCell'
                    when hiyo_kbn=4 then 'bottomHTankaCell'
                    when hiyo_kbn=5 then 'disHTankaCell'
                end
                 as hClassName"),
            DB::raw("
                case
                    when hiyo_kbn=0 then 'gakuCell'
                    when hiyo_kbn=1 then 'zatuGakuCell'
                    when hiyo_kbn=2 then 'keihiGakuCell'
                    when hiyo_kbn=3 then 'kansetuGakuCell'
                    when hiyo_kbn=4 then 'bottomGakuCell'
                    when hiyo_kbn=5 then 'disGakuCell'
                end
                 as gClassName"),

            DB::raw("
                case
                    when zai_kbn like '_A%' then 'azai' /*A材*/
                    when zai_kbn like '_B%' then 'bzai' /*B材*/
                    else 'etczai' /*その他*/
                end
                 as zai_kbn
                    "),

            "mitumoriSais.tani",
            "mitumoriSais.sizaiId",
            "mitumoriSais.bugakariDe",
            "mitumoriSais.bugakari",
            "mitumoriSais.bugakariTo",
            "mitumoriSais.kansetu_code",//間接費なら元となる間接費はなにか
            "mitumoriSais.hiyo_kbn",//間接費があるか
            "mitumoriSais.tanka_rendo_code",
            "mitumoriSais.bugakari_rendo_code",
            "mitumoriSais.tekkyo_rate",
            
            DB::raw("
            if(hiyo_kbn=0,
                concat(
                    (case
                        when zai_kbn like '_A%' and seko_kbn in (0,1) then 'A' /*A材*/
                        when zai_kbn like '_B%' and seko_kbn in (0,1) then 'B' /*B材*/
                        else '' /*その他*/
                    end),
                    (case
                        when seko_kbn=0 then '行追加'
                        when seko_kbn=1 then '新設'
                        when seko_kbn=2 then '撤去(再)'
                        when seko_kbn=3 then '撤去'
                        when seko_kbn=4 then '再取付'
                    end)
                    
                )
                ,
            ''
            ) as sai_status"),//最初のstatus

            DB::raw("su*bugakariDe*tekkyo_rate as bugakariDeSum"),
            DB::raw("su*bugakari*tekkyo_rate as bugakariSum"),
            DB::raw("su*bugakariTo*tekkyo_rate as bugakariToSum"),
            DB::raw("
            round(bugakariDe*mitumoriSais.tekkyo_rate*'$romTankaDe')+
            round(bugakari*mitumoriSais.tekkyo_rate*'$romTanka')+
            round(bugakariTo*mitumoriSais.tekkyo_rate*'$romTankaTo')
             as hukugoRomGaku"),
            DB::raw("
                round(cast(
                    (
                        round(bugakariDe*mitumoriSais.tekkyo_rate*'$romTankaDe')+
                        round(bugakari*mitumoriSais.tekkyo_rate*'$romTanka')+
                        round(bugakariTo*mitumoriSais.tekkyo_rate*'$romTankaTo')+
                        if(hiyo_kbn in(1,3) and '$kansetu_auto_calculate'=1,auto_tanka,tanka)
                    )*su 
                as decimal))
            as hukugoGaku"),

            "mitumoriSais.siyo as sizai_name",
            "mitumoriSais.hiyo_kbn"
            )
            ->orderBy("hiyo_kbn","asc")
            ->orderBy("gyoNoI","asc")
            ->get();
        
        if($mitumori->hukugo_tanka_flg==1){
            $view='mitumoriSetubiKani.indexHukugo';
        }else{
            $view='mitumoriSetubiKani.index';
        }

        //キープしている登録しようとしている資材データを消しておく
        DB::table("keep_sizais")->where("mitumoriId",$id)->delete();

        return view($view,[
            "tanis"=>array_column(DB::table("tanis")->where("use_datalist",1)->get()->toArray(),"name"),
            "totalGaku"=>$totalGaku,
            "mitumori"=>$mitumori,
            "mitumoriSais"=>$mitumoriSais,
            "kozis"=>DB::table("kozi_category_names")->get(),
            "keihis"=>DB::table("keihis")->get(),
            "companies"=>DB::table("companies")->get(),
            "kansetuKoumokus"=>$kansetuKoumokus,
            "kansetus"=>$kansetus,
            "selectedCode"=>$selectedCode,
            "roms"=>DB::table("rom_tankas")->get(),
            "users"=>DB::table("users")->get(),
            // "auth"=>Auth::user(),
            "future"=>$future,
            "past"=>$past
            ]);
    }

    public function mitumoriUpdate($id,Request $request){
        DB::table("mitumoris")->where("id",$id)
        ->update([
            "title"=>(string)$request->title,
            "atesaki"=>(string)$request->atesaki,
            "keisyo"=>(string)$request->keisyo,
            "area"=>(string)$request->area,
            "hukugo_tanka_flg"=>(boolean)$request->hukugo_tanka_flg,
            "torihikiho"=>(string)$request->torihikiho,
            "kigen"=>(string)$request->kigen,
            "biko"=>(string)$request->biko,
            "memo"=>(string)$request->memo,
            "tantoName"=>(string)$request->tanto,
            "print_at"=>$request->print_at,
            // "do_at"=>$request->do_at,
            "tax_rate"=>$request->tax_rate,
            // "loginId"=>Auth::id(),
            "loginTime"=>carbon::now(),
        ]);
        return \redirect()->back()->with("success","見積を更新しました。");
    }

    //間接費再計算
    public function kansetuAgain($id){
        //間接費計算
        $this->kansetuhiService->kani($id);
        return \redirect()->back();
    }

    //行追加
    public function addRow($id ,Request $request){
        //現在を過去に送る====
        $this->RekiService->rekiStore($id,"past",1);
        $this->RekiService->rekiDelete("future");
        //===================
        DB::table("mitumoriSais")
        ->insert([
            "mitumoriId"=>$id,
            "created_at"=>Carbon::now(),
            "updated_at"=>Carbon::now(),
            "hiyo_kbn"=>$request->keihi_flg??0,
            "zai_kbn"=>$request->zai_kbn,
            "name"=>"",
            "tani"=>"",
            "tanka"=>0,
            "su"=>0,
            "gaku"=>0,
        ]);
    
        return \redirect()->back();
    }


    public function sort(Request $request){
        //先に見積項目のidを取得
        $idArray=$request->idArray;
        // $mitumoriKoId=$request->mitumoriId;

        $m=DB::table("mitumoriSais")
            ->where("id",$idArray[0])
            ->first();

        //現在を過去に送る====
        $this->RekiService->rekiStore($m->mitumoriId,"past",1);
        $this->RekiService->rekiDelete("future");
        //===================

        foreach($idArray as $key=>$id){
            DB::table("mitumoriSais")
            ->where("id",$id)
            ->update(["gyoNo"=>$key+1]);
        }
        
        return response()->json(["messege"=>"finish"]);
    }

    //経費行追加
    public function addKeihi($id ,Request $request){

        //現在を過去に送る====
        $this->RekiService->rekiStore($id,"past",1);
        $this->RekiService->rekiDelete("future");
        //===================

        if($request->keihi==null){
            $name="";
            $tani="";
            $tanka=0;
        }else{
            $keihi=DB::table("keihis")->where("id",$request->keihi)->first();
            $name=$keihi->name;
            $tani=$keihi->tani;
            $tanka=$keihi->tanka;
        }

        $su=0;
        $hiyo_kbn=2;
        if($name=="【値引き】"){
            $hiyo_kbn=5;
            $su=1;
        }
        
        DB::table("mitumoriSais")
        ->insert([
            "mitumoriId"=>$id,
            "created_at"=>Carbon::now(),
            "updated_at"=>Carbon::now(),
            "hiyo_kbn"=>$hiyo_kbn,
            "name"=>$name,
            "tani"=>$tani,
            "tanka"=>$tanka,
            "su"=>$su,
            "gaku"=>0,
        ]);
    
        return \redirect()->back();
    }

    //資材表示させるために必要なアクション
    public function jstree(Request $request){
        
        //最初の階層
        if($request->id =="#"){
            
            $data = DB::table("sizai_buns")
            ->where("setubi_flg",1)
            ->whereNull("deleted_at")
            ->select("name as text","code as id",DB::raw("true as children"))
            ->orderByRaw("denki_flg = 0 desc")//設備だけチェックのデータは上にする
            ->get();
            foreach($data as $key=>$value){
                $value->children = true;
            }
            return response()->json($data);

        //2階層目
        }elseif(strlen($request->id) ==3){
            $data = DB::table("sizai_mokus")
            ->where("code","like",$request->id.'%')
            ->whereNull("deleted_at")
            ->select("name as text","code as id",DB::raw("true as children"))
            ->orderBy("sizai_mokus.sortno")
            ->get();
            foreach($data as $key=>$value){
                $value->children = true;
            }
            return response()->json($data);
        
        //3階層目
        }elseif(!str_contains($request->id,"_anchor")){

            $ymd=DB::table("sizai_kikakus")->max("dvd_ymd");        

            //最新のDVDデータまたは自作資材のみ表示させる。
            $kikakus=DB::table("sizai_kikakus")
            ->where("code","like",$request->id.'%')
            // ->where(function($query) use($ymd){
            //     $query->where("dvd_ymd",$ymd)
            //     ->orWhere("made_in_me","1");
            // })
            ->select("code")
            ->groupBy("code");

            $data = DB::table("sizai_names")
            ->where("sizai_names.code","like",$request->id.'%')
            ->joinSub($kikakus,"kikakus",function($join){
                $join->on("sizai_names.code",DB::raw("left(kikakus.code,11)"));
            })
            ->distinct()
            ->select("name as text","sizai_names.code as id",DB::raw("'jstree-file' as icon"))
            ->orderBy("sizai_names.sortno")
            ->get();
            return response()->json($data);
        
        //ウィンドウ表示
        }else{

            $mitumoriId= last(explode("/", url()->previous()));
            
            //最新のDVDデータまたは自作資材のみ表示させる。
            $ymd=DB::table("sizai_kikakus")->max("dvd_ymd");
            $code=str_replace("_anchor","",$request->id);
            $data = DB::table("sizai_kikakus")
            ->where("code","like",$code.'%')
            ->where(function($query)use($mitumoriId){
                $query->where("sizai_kikakus.mitumoriId",null)->orWhere("sizai_kikakus.mitumoriId",$mitumoriId);
            })
            ->leftjoin("kikaku_name_for_mitumoris",function($join)use($request){
                $join->on("kikaku_name_for_mitumoris.kikakuId","=","sizai_kikakus.id")
                    ->where("kikaku_name_for_mitumoris.mitumoriId",$request->mitumoriId);
            })
            ->select(
                "sizai_kikakus.id",
                "sizai_kikakus.mitumoriId",
                "sizai_kikakus.bugakaA01",
                "sizai_kikakus.bugakaA02",
                "sizai_kikakus.bugakaA03",
                "sizai_kikakus.bugakaA04",
                "sizai_kikakus.bugakaA05",
                "sizai_kikakus.bugakaA06",
                "sizai_kikakus.bugakaA07",
                "sizai_kikakus.bugakaA08",
                "sizai_kikakus.bugakaA09",
                "sizai_kikakus.bugakaA10",
                // "sizai_kikakus.bugakaA11",
                // "sizai_kikakus.bugakaA12",
                DB::raw("if(kikaku_name_for_mitumoris.id IS NULL,sizai_kikakus.name,kikaku_name_for_mitumoris.name) as name")
                )
            ->orderByRaw("sizai_kikakus.mitumoriId is not null desc")
            ->orderByRaw("sizai_kikakus.gyoNo asc")
            ->orderByRaw("sizai_kikakus.id asc")
            ->get();

            
        
            $keepSizai=DB::table("keep_sizais")
            ->where("mitumoriId",$mitumoriId)
            ->get();
            $keepSizaiArray=[];
            foreach($keepSizai as $key=>$value){
                $keepSizaiArray[$value->kikakuId][$value->sekoCode]=["su"=>$value->su];
            }


            $sizai_name = DB::table("sizai_names")->where("code",$code)->select("*")->first();
            $sekos = DB::table("sekos")->select("*")->get();
            
            $sekoNames=[];
            $sekoNames[]=["code"=>0,"name"=>"なし"];
            foreach ($sekos as $key => $seko) {
                $sekoNames[$seko->code]=["code"=>$seko->code,"name"=>$seko->name];
            }
                        
            $sekoCodes=[];
            $sekoCodes2=[];
            $kikakuHeader[]="規格";
            $kikakuHeader2[]="規格";
            for ($i=1; $i<=10; $i++) {
                if($sizai_name->{"seko".$i}!=0 && $i!=11 && $i!=12){
                    $kikakuHeader[]=$sekoNames[$sizai_name->{"seko".$i}]["name"];
                    $sekoCodes[$i]["code"]=$sizai_name->{"seko".$i};
                    $sekoCodes[$i]["bugakariColumn"]="bugakaA".\str_pad($i, 2, "0", STR_PAD_LEFT);
                    $kikakuHeader2[]=$sekoNames[$sizai_name->{"seko".$i}]["name"];
                    $sekoCodes2[$i]["code"]=$sizai_name->{"seko".$i};
                    $sekoCodes2[$i]["bugakariColumn"]="bugakaA".\str_pad($i, 2, "0", STR_PAD_LEFT);   
                
                }else{
                    $kikakuHeader2[]="なし";
                    $sekoCodes2[$i]["code"]=$sizai_name->{"seko".$i};
                    $sekoCodes2[$i]["bugakariColumn"]="bugakaA".\str_pad($i, 2, "0", STR_PAD_LEFT);
                }
            }

            if(count($kikakuHeader)==1){
                $kikakuHeader[]="";
                $sekoCodes[0]["code"]="0";
                $sekoCodes[0]["bugakariColumn"]="bugakaA01";
            }

            
            //歩掛入っているか確認用配列
            $bugakariArray=[];
            foreach($data as $d){
                foreach($sekoCodes as $key=>$sekoCode){
                    $bugakariArray[$d->id][$sekoCode["code"]]=$d->{$sekoCode["bugakariColumn"]}==0?"歩掛未登録":"";
                }
            }

            //歩掛の値を入れる
            $bugakariEditArray=[];
            foreach($data as $d){
                foreach($sekoCodes2 as $key=>$sekoCode2){
                    $bugakariEditArray[$d->id][]=["v"=>$d->{$sekoCode2["bugakariColumn"]},"c"=>$sekoCode2["bugakariColumn"]];
                }
            }

            return response()->json([
                "data"=>$data,
                "bugakariArray"=>$bugakariArray,
                "bugakariEditArray"=>$bugakariEditArray,
                "sizai_name"=>$sizai_name,
                "keepSizais"=>$keepSizaiArray,
                "sekoNames"=>$sekoNames,
                "sekoCodes"=>\json_encode($sekoCodes),
                "sekoCodes2"=>\json_encode($sekoCodes2),
                "kikakuHeader"=>$kikakuHeader,
                "kikakuHeader2"=>$kikakuHeader2
            ]);
        }
    }

    public function keepSizai(Request $request){
        $data=$request->all();

        $sizaiName=DB::table("sizai_kikakus")
        ->where("sizai_kikakus.id",$data["kikakuId"])
        ->join("sizai_names","sizai_names.code","=",DB::raw("left(sizai_kikakus.code,11)"))
        ->select("sizai_names.*")->first();

        if($data["sekoCode"]==null) $data["sekoCode"]="";
        //空なら
        $data["su"]=(int)$data["su"];
        
            DB::table("keep_sizais")
            ->updateOrInsert(
            [
                "kikakuId"=>$data["kikakuId"],
                "sizaiCode"=>$sizaiName->code,
                "sekoCode"=>$data["sekoCode"],
                "bugakariColumn"=>$data["bugakariColumn"],
                "mitumoriId"=>$data["mitumoriId"]
            ],  
            [
                "su"=>$data["su"],
            ]);
        

        if(!DB::table("sekos")->where("code",$data["sekoCode"])->exists()){ //空の時
            $sekoName="";
        }else{
            $sekoName=DB::table("sekos")->where("code",$data["sekoCode"])->first()->name;
        }

        $kikakus=DB::table("sizai_kikakus")
        ->where("sizai_kikakus.id",$data["kikakuId"])
        ->join("sizai_names","sizai_names.code","=",DB::raw("left(sizai_kikakus.code,11)"))
        ->leftjoin("sekos","sekos.code","=","sizai_kikakus.".$data["bugakariColumn"])//sekoCodeの中身はseko1~seko30
        ->leftjoin("kikaku_name_for_mitumoris",function($join)use($data){
                        $join->on("kikaku_name_for_mitumoris.kikakuId","=","sizai_kikakus.id")
                            ->where("kikaku_name_for_mitumoris.mitumoriId",$data["mitumoriId"]);
                    })
        ->select(
            "sizai_kikakus.id as kikakuId",
            "sizai_names.name as sizaiName",
            DB::raw("if(kikaku_name_for_mitumoris.id IS NULL,sizai_kikakus.name,kikaku_name_for_mitumoris.name) as kikakuName"),
            DB::raw("'$sekoName' as sekoName"),
            DB::raw("'$data[sekoCode]' as sekoCode"),
            DB::raw("'$data[su]' as su")
        )
        ->first();

        return response()->json($kikakus);
    }

    //歩掛編集
    public function editSizai(Request $request){
        $data=$request->all();

        $url=$_SERVER['HTTP_REFERER'];
        $urlArray=explode("/",$url);
        $urlArrayInt=count($urlArray);
        $mId=$urlArray[$urlArrayInt-1];


        //資材ネーム変更
        if($data["table"]=="kikaku_name_for_mitumoris"){
            DB::table("kikaku_name_for_mitumoris")
            ->updateOrInsert(
                [
                    "mitumoriId"=>$mId,
                    "kikakuId"=>$data["id"]
                ],
                [
                    $data["bugakariColumn"]=>(string)$data["v"]
                ]
            );
        }else{
            if($data["table"]=="sizai_kikakus") $data["v"]=(float)$data["v"];
            // 規格又は資材名
            DB::table($data["table"])
            ->where("id",$data["id"])
            ->update([
                $data["bugakariColumn"]=>$data["v"]
            ]);
        }

        return response()->json();
    }

    

    //資材追加
    public function store(Request $request){

        //現在を過去に送る====
        $this->RekiService->rekiStore($request->mitumoriId,"past",1);
        $this->RekiService->rekiDelete("future");
        //===================

        $keeps=DB::table("keep_sizais")
        ->where("mitumoriId",$request->mitumoriId)
        ->orderBy("id")
        ->get();

        //もしkeepにデータがなかったらそのままもどる
        if($keeps->count()==0){
            return \back();
        }

        $now=carbon::now()->format("Y-m-d H:i:s");
        $suArray=array_column($keeps->toArray(),"su");
        $kikakuIdArray=array_column($keeps->toArray(),"kikakuId");
        $sekoArray=array_column($keeps->toArray(),"sekoCode");
        $sagyoSyu=$request->sagyoSyu;//作業員種類　den:電工, hutu:普通作業員, toku:特殊作業員
        $sekoArrayUnique = array_unique($sekoArray);
        $sekoData=DB::table("sekos")->whereIn("code",$sekoArrayUnique)->select("code","name")->get();
        $sizaiCode=array_column($keeps->toArray(),"sizaiCode");
        $bugakariColumn=array_column($keeps->toArray(),"bugakariColumn");
        $m=DB::table("mitumoris")->where("id",$request->mitumoriId)->first();
        $insertArray=[];

        foreach ($keeps as $key => $keep) {
            if($keep->su==0)continue;
            $sizai=DB::table("sizai_names")->where("code",$keep->sizaiCode)->select("name")->first();
            if($request->sekoSyu == "teSai" || $request->sekoSyu == "te"){
                $tekkyo=DB::table("sizai_mokus")->where("code",substr($keep->sizaiCode,0,7))->select("tekkyo")->first()->tekkyo;     
                
                //撤去再利用        
                if($request->sekoSyu == "teSai"){
                    $tekkyoRate=DB::table("tekkyo_rates")->where("code",$tekkyo)->first()->ari;
                    $biko="撤去(再利用)";
                    $seko_kbn=2;
                //ただの撤去
                }else{
                    $tekkyoRate=DB::table("tekkyo_rates")->where("code",$tekkyo)->first()->nasi;
                    $biko="撤去";
                    $seko_kbn=3;
                }
                // 撤去は単価0
                $tankaRate=0;
            
            //再取付
            }elseif($request->sekoSyu == "sai"){
                $tekkyoRate=1;
                $tankaRate=0;
                $biko="再取付";
                $seko_kbn=4;
            //新設
            }else{
                $tekkyoRate=1;
                $tankaRate=1;
                $biko="";
                $seko_kbn=1;
            }

            

            $sizai_kikaku=DB::table("sizai_kikakus")
                    ->where("sizai_kikakus.id",$keep->kikakuId)
                    ->leftjoin("kikaku_name_for_mitumoris",function($join)use($m){
                        $join->on("kikaku_name_for_mitumoris.kikakuId","=","sizai_kikakus.id")
                            ->where("kikaku_name_for_mitumoris.mitumoriId",$m->id);
                    })
                    ->select(
                        $keep->bugakariColumn,
                        "tani",
                        "tanka1 as tanka",
                        DB::raw("if(kikaku_name_for_mitumoris.id IS NULL,sizai_kikakus.name,kikaku_name_for_mitumoris.name) as name"),
                        "yosokake",
                        "bugakaA11",
                        "bugakaA12"
                        )->first();

                $sekoName=$sekoData->where("code",$keep->sekoCode)->first();

                if($sekoName==null){
                    $sekoName="";
                }else{
                    $sekoName=$sekoName->name;
                }
                
                $insertArray[]=[
                    "mitumoriId"=>$request->mitumoriId,
                    "zai_kbn"=>$sizai_kikaku->yosokake,
                    "su"=>$keep->su,
                    "siyo"=>$sizai_kikaku->name." ".$sekoName,
                    "seko"=>$keep->sekoCode??"",
                    "sizaiCode"=>$keep->sizaiCode,//エラー発生時、php.iniのpost_max_sizeを超えるとsizaiCodeがnullになる
                    "sizaiId"=>$keep->kikakuId,   
                    "name"=>$sizai->name,
                    "biko"=>$biko,                              /*中にカラム名が入っている*/
                    "bugakariDe"=>$sagyoSyu=="den"?$sizai_kikaku->{$keep->bugakariColumn}:0,
                    "bugakari"=>$sagyoSyu=="hutu"?$sizai_kikaku->bugakaA11:0,//普通作業員
                    "bugakariTo"=>$sagyoSyu=="toku"?$sizai_kikaku->bugakaA12:0,//特殊作業員
                    "sagyoin_kbn"=>$sagyoSyu=="den"?1:($sagyoSyu=="hutu"?2:3),
                    "seko_kbn"=>$seko_kbn,
                    "tekkyo_rate"=>$tekkyoRate,
                    "tani"=>(string)$sizai_kikaku->tani,
                    "tanka"=>round($sizai_kikaku->tanka)*$tankaRate,
                    "gaku"=>(round($sizai_kikaku->tanka)*$tankaRate)*$keep->su,
                    "tanka_rendo_code"=>$keep->kikakuId.$request->sekoSyu,
                    "bugakari_rendo_code"=>((string)$keep->kikakuId).((string)$keep->sekoCode).((string)$sagyoSyu=="den"?1:($sagyoSyu=="hutu"?2:3)),
                    "created_at"=>$now,
                    "updated_at"=>$now
                ];            
        }
        
        //===================================================
        //同じ資材IDのものがあるか確認して、あれば同じ単価にする
        //同じ見積りで同じ資材は同じ金額になるようにする
        $saisKikakuChack=DB::table("mitumoriSais")
        ->whereIn("sizaiId",array_column($insertArray,"sizaiId"))
        ->where("mitumoriId",$request->mitumoriId)->get();

        foreach ($insertArray as &$insert) {
            foreach ($saisKikakuChack as $sais) {
                //単価を合わせる
                // if($insert['sizaiId']==$sais->sizaiId){
                //     $insert['name']=$sais->name;
                //     $insert['siyo']=$sais->siyo;
                // }

                //単価を合わせる
                if($insert['tanka_rendo_code']==$sais->tanka_rendo_code){
                    $insert['tanka']=$sais->tanka;
                }
                //歩掛を合わせる
                if($insert['bugakari_rendo_code']==$sais->bugakari_rendo_code ){
                    $insert['bugakariDe']=$sais->bugakariDe;
                    $insert['bugakari']=$sais->bugakari;
                    $insert['bugakariTo']=$sais->bugakariTo;
                }
            }
        }
        //===================================================

        DB::table("mitumoriSais")->insert($insertArray);
        DB::table("keep_sizais")->where("mitumoriId",$m->id)->delete();
        return \back();
    }

    public function copy($id ,Request $request){
        
        $data=MitumoriSai::find($request->saiId);
        //現在を過去に送る====
        $this->RekiService->rekiStore($id,"past",1);
        $this->RekiService->rekiDelete("future");
        //===================
        
        foreach(MitumoriSai::where("mitumoriId",$id)->where("gyoNo",">=",$data->gyoNo+1)->get() as $key=>&$d){
            $d->gyoNo+=1;
            $d->save();
        };
        $newData=$data->replicate();
        $newData->gyoNo=$data->gyoNo+1;
        $newData->save();
        
        return response()->json(['id' => $newData->id]);

    }

    public function storeKansetu(Request $request){

        DB::table("kansetu_calculations")->where('mitumoriId',$request->mitumoriId)->delete();

        $kansetu_komokus=DB::table("kansetu_komokus")
        ->where("bun_code",$request->kansetu_code)
        ->where("yoso_ysan","<>","")
        ->get();

        $insertArray=[];
        foreach ($kansetu_komokus as $key=>$kikaku) {
            //510以外は率は決まっているため0で統一
            if($kikaku->bun_code==510 || in_array($kikaku->code,[5,6,7])){
                $kansetu_rate=$request->{'kansetu_rate_'.$kikaku->bun_code.'_'.$kikaku->code};
            }else{
                $kansetu_rate=0;
            } 
            //間接費のかけ率を保存
            $insertArray[]=[
                'name'=>$kikaku->name,
                'mitumoriId'=>$request->mitumoriId,
                "kansetu_rate"=>(float)$kansetu_rate,
                "kansetu_code"=>$kikaku->code,
                "kansetu_bun_code"=>$kikaku->bun_code,
                "created_at"=>Carbon::now(),
                "updated_at"=>Carbon::now()
            ];      
        }
        DB::table("kansetu_calculations")->insert($insertArray);

        //間接費のかけ率を更新
        $this->kansetuhiService->kani($request->mitumoriId);

        return back();
    }


    //間接費の結果をajaxに返す。
    public function kansetuAjax(Request $request){
        //間接費計算
        $this->kansetuhiService->kani($request->id);
        
        $kansetu=DB::table("mitumoriSais")
        ->join("mitumoris",function($join) use($request){
            $join->on("mitumoris.id","=","mitumoriSais.mitumoriId")
                 ->where("mitumoris.id","=",$request->id);
        })
        ->where("mitumoriSais.mitumoriId",$request->id)
        ->where("mitumoriSais.hiyo_kbn",3)
        ->select(
            "mitumoriSais.kansetu_code",
            DB::raw("if(mitumoris.kansetu_auto_calculate=1,mitumoriSais.auto_tanka,mitumoriSais.tanka) as tanka")
        )->get();
    
        return response()->json($kansetu);
    }



    public function delete($id){
        if(DB::table("mitumoriSais")->where("id",$id)->exists()){
            //先に見積項目のidを取得
            $mId=DB::table("mitumoriSais")->where("id",$id)->first()->mitumoriId;

            //現在を過去に送る====
            $this->RekiService->rekiStore($mId,"past",1);
            $this->RekiService->rekiDelete("future");
            //===================

            DB::table("mitumoriSais")->where("id",$id)->delete();
        }
        return \back();
    }

    public function update(Request $request){
        
        $sai=DB::table("mitumoriSais")->where("id",$request->id)->first();

        if(in_array($request->name,["tanka"]))$request->value=(int)$request->value;
        
        if(in_array($request->name,['name','siyo','tani','biko']))$request->value=(string)$request->value;
        
        if(preg_match('/ritu/',$request->name) ||  in_array($request->name,['bugakariDe','bugakari','bugakariTo','su'])){
            $request->value=(float)$request->value;
        }
        if(DB::table("mitumoriSais")->where("id",$request->id)->first()->hiyo_kbn==5 && $request->name=="tanka"){
            $request->value=-abs($request->value);
        }
        
        DB::table("mitumoriSais")->where("id",$request->id)->update([
            $request->name=>$request->value
        ]);

        //単価の時、同じ、資材ののものはすべて同じ値にさせる
        if(in_array($request->name,["tanka"]) && $sai->tanka_rendo_code!=''){
            DB::table("mitumoriSais")
            ->where("mitumoriId",$sai->mitumoriId)
            ->where("tanka_rendo_code",$sai->tanka_rendo_code)
            ->update([$request->name=>$request->value]);
        }elseif(in_array($request->name,["bugakariDe","bugakari","bugakariTo"]) && $sai->bugakari_rendo_code!=''){
            DB::table("mitumoriSais")
            ->where("mitumoriId",$sai->mitumoriId)
            ->where("bugakari_rendo_code",$sai->bugakari_rendo_code)
            ->update([$request->name=>$request->value]);
        }


        return response()->json(["column"=>$request->name,"v"=>$request->value]);
    }

    public function mitumoriOnChange(Request $request){

        if($request->name=='kansetu_auto_calculate'){
             // 現在を過去に送る====
            $this->RekiService->rekiStore($request->id,"past",1);
            $this->RekiService->rekiDelete("future");
            // ===================

            $request->value=$request->value=='true'?1:0;
            $first_auto_changed_flg=DB::table("mitumoris")
            ->where("id",$request->id)
            ->first()->first_auto_changed_flg;
            
            //初めて自動計算offにしたときに値をうつす
            if($first_auto_changed_flg==0){
                DB::table("mitumoriSais")
                ->where("mitumoriId",$request->id)
                ->where(function($query){
                    $query->where("hiyo_kbn",3)
                        ->orWhere("hiyo_kbn",1);
                })
                ->update(["tanka"=>DB::raw("auto_tanka")]);
                //最初の変更フラグを立てる今後は自動計算単価を単価に移行しない
                DB::table("mitumoris")
                ->where("id",$request->id)
                ->update(["first_auto_changed_flg"=>1]);
            }
        }elseif(in_array($request->name,['rom_tankaDe','rom_tanka','rom_tankaTo','rom_gaku'])){
            $request->value=(float)$request->value;
        }else{
            $request->value=(string)$request->value;
        }

        DB::table("mitumoris")->where("id",$request->id)->update([
            $request->name=>$request->value
        ]);
        
        return response()->json(["column"=>$request->name,"v"=>$request->value]);
    }

    public function domPdf($id,$companyId,$pdfType){
        $mitumori=DB::table("mitumoris")->where("mitumoris.id",$id)->first();
        $romTankaDe=$mitumori->rom_tankaDe;
        $romTanka=$mitumori->rom_tanka;
        $romTankaTo=$mitumori->rom_tankaTo;
        $hukugo_tanka_flg=$mitumori->hukugo_tanka_flg;
        
        $aimitu_flg=$mitumori->aimitu_flg;
        $hukugo_tanka_flg=$mitumori->hukugo_tanka_flg;
        $company=DB::table("companies")->where("id",$companyId)->first();
        $romgaku=Mitumori::withGaku($id)->first()->romGaku;
        
        $mitumoriSais=DB::table("mitumoriSais as mS")
            ->where("mS.mitumoriId",$id)
            ->join("mitumoris as mit","mit.id","=","mS.mitumoriId")
            ->select(
                "mS.su",
                "mS.tani",
                "mS.gyoNo",
                "mS.biko",
                "mS.hiyo_kbn",
                "mS.name",
                "mS.siyo",
                DB::raw("
                        if(
                        '$hukugo_tanka_flg'=0,
                        /*一般単価*/
                        if(mit.kansetu_auto_calculate=1 and mS.hiyo_kbn in(1,3) ,mS.auto_tanka,mS.tanka),
                        

                        /*複合単価*/
                        round(bugakariDe*mS.tekkyo_rate*'$romTankaDe')+
                        round(bugakari*mS.tekkyo_rate*'$romTanka')+
                        round(bugakariTo*mS.tekkyo_rate*'$romTankaTo')+
                        if(mit.kansetu_auto_calculate=1 and mS.hiyo_kbn in(1,3),mS.auto_tanka,mS.tanka)
                        )
                        as tanka"),
                
                DB::raw("
                        round(cast(
                            if(
                                '$hukugo_tanka_flg'=0,
                                /*一般単価*/
                                if(mit.kansetu_auto_calculate=1 and mS.hiyo_kbn in(1,3),mS.auto_tanka,mS.tanka),

                                /*複合単価*/
                                round(bugakariDe*mS.tekkyo_rate*'$romTankaDe')+
                                round(bugakari*mS.tekkyo_rate*'$romTanka')+
                                round(bugakariTo*mS.tekkyo_rate*'$romTankaTo')+
                                if(mit.kansetu_auto_calculate=1 and mS.hiyo_kbn in(1,3),mS.auto_tanka,mS.tanka)
                            )*su
                        as decimal))
                    as gaku"),
                DB::raw("
                    if(mS.hiyo_kbn != 0,
                        mS.name,
                        concat(
                            if( 
                                mS.gyoText != '' and mS.gyoText IS NOT NULL,
                                mS.gyoText,
                                mS.gyoNo
                            ),
                            '.',
                            name,'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',siyo)
                    ) as noNameSiyo"),
                
                //
                DB::raw("
                    if(mS.hiyo_kbn = 0,
                        if( 
                            mS.gyoText != '' and mS.gyoText IS NOT NULL,
                            mS.gyoText,
                            mS.gyoNo
                        ),''
                    ) as lineNo"),
                

                DB::raw("
                    '$romgaku'
                     as rom_gaku"),
                )
            ->orderBy("mS.hiyo_kbn","asc")
            ->orderBy("mS.gyoNo","asc")
            ->get()
            ->filter(function($v){
                //資材経費は常に表示　間接費消耗品雑材は金額が0のときは非表示
                return in_array($v->hiyo_kbn,[0,2,4,5]) || $v->hiyo_kbn == 3 && $v->gaku <> 0 || $v->hiyo_kbn == 1 && $v->gaku <> 0;
            });

        //文字数オーバーで改行させる
        foreach($mitumoriSais->whereIn("hiyo_kbn",[0]) as &$sai){
            $name=$sai->lineNo.'.'.$sai->name.$sai->siyo;
            $nameLength = (mb_strwidth($name,'UTF-8')/2)+3.5;//余白3.5幅分
            if($nameLength >= 30){
                $left=10;
                $left+=mb_strwidth($sai->lineNo,'UTF-8')*6;
                $sai->noNameSiyo="
                                    <span style=''>
                                        <span style='font-size:10px; position: absolute; left:7px; top:0px; z-index:99;'>".$sai->lineNo.".</span>
                                        <span style='font-size:9px; position: absolute; left:".$left."px; top:-3px; z-index:99;'>".$sai->name."</span>
                                        <span style='font-size:9px; position: absolute; left:".$left."px; top:7px; z-index:99;'>".$sai->siyo."</span>
                                    </.span>
                                
                            ";
            }
        }
        

        
        

        //複合単価なら労務費は0
        if($hukugo_tanka_flg==0 && $romgaku>0){
            $roumhi=($mitumoriSais[0]->rom_gaku??0)+($mitumoriSais[0]->rom_gakuDe??0)+($mitumoriSais[0]->rom_gakuTo??0);
            $romRow=(object)[
                    "su"=> 1,
                    "tani"=>"式",
                    "gyoNo"=> null,
                    "biko"=> "",
                    "hiyo_kbn"=> 2.5,
                    "name"=> "労務費",
                    "siyo"=> "",
                    "tanka"=> $roumhi,
                    "gaku"=> $roumhi,
                    "noNameSiyo"=> "労務費",
                    "rom_gaku"=> $romgaku
                ];
                $mitumoriSais2=[];
                $romAdded=0;
                foreach($mitumoriSais as $mitumoriSai){
                    if($mitumoriSai->hiyo_kbn==3 && $romAdded==0){
                        $mitumoriSais2[]=$romRow;
                        $romAdded=1;
                    }
                    $mitumoriSais2[]=$mitumoriSai;
                }
                $mitumoriSais = collect($mitumoriSais2);   
        }





             
        $mitumori=Mitumori::PdfStyle($id);

        //和暦
        $ymd = Date::parse($mitumori->print_at);  // 2025-07-18
        $jy=$ymd->toWareki()->format('y年n月j日');
        //社印・担当印も取得
        $COM_stamp=null;
        $PIC_stamp=null;
        $PIC = DB::table('users')->where('name','=',$mitumori->tantoName)->first();
        $PIC_id=$PIC?$PIC->id:0;
        
        if($aimitu_flg == 0){
            if(file_exists(public_path('stamp_img/companystamp.png'))){
                $COM_stamp=base64_encode(file_get_contents(public_path('stamp_img/companystamp.png')));
            }
            if(file_exists(public_path('stamp_img/'.$PIC_id.'.png'))){
                $PIC_stamp=base64_encode(file_get_contents(public_path('stamp_img/'.$PIC_id.'.png')));
            }
        }

        // return view()->make('kaniPdf.pdf'.$pdfType,[
        //     "mitumori"=>$mitumori,
        //     "company"=>DB::table("companies")->where("id",$companyId)->first(),
        //     // "roumhi"=>$roumhi,
        //     "mitumoriSais"=>$mitumoriSais,
        //     "today"=>$jy,
        //     "COM_stamp"=>$COM_stamp,
        //     "PIC_stamp"=>$PIC_stamp
        // ]);

        $pdf = PDF::loadView('kaniPdf/pdf'.$pdfType,[
            "mitumori"=>$mitumori,
            "company"=>DB::table("companies")->where("id",$companyId)->first(),
            // "roumhi"=>$roumhi,
            "mitumoriSais"=>$mitumoriSais,
            "today"=>$jy,
            "COM_stamp"=>$COM_stamp,
            "PIC_stamp"=>$PIC_stamp
            ])
            ->set_option('compress', 1)
            ->set_option("isPhpEnabled", true)
            ->setPaper('a4', 'portrait'); // 縦A4サイズに指定

        // Render the PDF
        $pdf->render();
        $canvas = $pdf->get_canvas();

        //pdfTypeが1の時は見積番号を表示
        if($company->my_flg==1){
            $canvas->page_text(58, 19,'No.'.$mitumori->code, null, 8, [0, 0, 0]);
        }

        $fileName = '見積書.pdf';        
        return $pdf->stream();

    }

    public function excel($id,$companyId,$pdfType){

        $mitumori=DB::table("mitumoris")->where("mitumoris.id",$id)->first();
        $romTankaDe=$mitumori->rom_tankaDe;
        $romTanka=$mitumori->rom_tanka;
        $romTankaTo=$mitumori->rom_tankaTo;
        $hukugo_tanka_flg=$mitumori->hukugo_tanka_flg;
        
        $aimitu_flg=$mitumori->aimitu_flg;
        $hukugo_tanka_flg=$mitumori->hukugo_tanka_flg;
        $company=DB::table("companies")->where("id",$companyId)->first();
        $romgaku=Mitumori::withGaku($id)->first()->romGaku;
        
        $mitumoriSais=DB::table("mitumoriSais as mS")
            ->where("mS.mitumoriId",$id)
            ->join("mitumoris as mit","mit.id","=","mS.mitumoriId")
            ->select(
                "mS.su",
                "mS.tani",
                "mS.gyoNo",
                "mS.biko",
                "mS.hiyo_kbn",
                DB::raw("if(mS.hiyo_kbn=0,concat(mS.gyoNo,'.',mS.name),mS.name) as name"),
                "mS.siyo",
                DB::raw("
                        if(
                        '$hukugo_tanka_flg'=0,
                        /*一般単価*/
                        if(mit.kansetu_auto_calculate=1 and mS.hiyo_kbn in(1,3) ,mS.auto_tanka,mS.tanka),
                        

                        /*複合単価*/
                        round(bugakariDe*mS.tekkyo_rate*'$romTankaDe')+
                        round(bugakari*mS.tekkyo_rate*'$romTanka')+
                        round(bugakariTo*mS.tekkyo_rate*'$romTankaTo')+
                        if(mit.kansetu_auto_calculate=1 and mS.hiyo_kbn in(1,3),mS.auto_tanka,mS.tanka)
                        )
                        as tanka"),
                
                DB::raw("
                        round(cast(
                            if(
                                '$hukugo_tanka_flg'=0,
                                /*一般単価*/
                                if(mit.kansetu_auto_calculate=1 and mS.hiyo_kbn in(1,3),mS.auto_tanka,mS.tanka),

                                /*複合単価*/
                                round(bugakariDe*mS.tekkyo_rate*'$romTankaDe')+
                                round(bugakari*mS.tekkyo_rate*'$romTanka')+
                                round(bugakariTo*mS.tekkyo_rate*'$romTankaTo')+
                                if(mit.kansetu_auto_calculate=1 and mS.hiyo_kbn in(1,3),mS.auto_tanka,mS.tanka)
                            )*su
                        as decimal))
                    as gaku"),
                DB::raw("
                    if(mS.hiyo_kbn != 0,
                        mS.name,
                        concat(
                            if( 
                                mS.gyoText != '' and mS.gyoText IS NOT NULL,
                                mS.gyoText,
                                mS.gyoNo
                            )
                        ,'.',name,'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',siyo)
                    ) as noNameSiyo"),
                 DB::raw("
                    if(mS.hiyo_kbn != 0,
                        mS.name,
                        concat(
                            if( 
                                mS.gyoText != '' and mS.gyoText IS NOT NULL,
                                mS.gyoText,
                                mS.gyoNo
                            )
                        ,'.',name,'\n',siyo)
                    ) as brLineName"),

                DB::raw("
                    '$romgaku'
                     as rom_gaku"),
                )
            ->orderBy("mS.hiyo_kbn","asc")
            ->orderBy("mS.gyoNo","asc")
            ->get()
            ->filter(function($v){
                //資材経費は常に表示　間接費消耗品雑材は金額が0のときは非表示
                return in_array($v->hiyo_kbn,[0,2,4,5]) || $v->hiyo_kbn == 3 && $v->gaku <> 0 || $v->hiyo_kbn == 1 && $v->gaku <> 0;
            });
        
        
        

        //複合単価なら労務費は0
        if($hukugo_tanka_flg==0 && $romgaku>0){
            $roumhi=($mitumoriSais[0]->rom_gaku??0)+($mitumoriSais[0]->rom_gakuDe??0)+($mitumoriSais[0]->rom_gakuTo??0);
            $romRow=(object)[
                    "su"=> 1,
                    "tani"=>"式",
                    "gyoNo"=> null,
                    "biko"=> "",
                    "hiyo_kbn"=> 4,
                    "name"=> "労務費",
                    "siyo"=> "",
                    "tanka"=> $roumhi,
                    "gaku"=> $roumhi,
                    "noNameSiyo"=> "労務費",
                    "rom_gaku"=> $romgaku
                ];
                $mitumoriSais2=[];
                $romAdded=0;
                foreach($mitumoriSais as $mitumoriSai){
                    if($mitumoriSai->hiyo_kbn==3 && $romAdded==0){
                        $mitumoriSais2[]=$romRow;
                        $romAdded=1;
                    }
                    $mitumoriSais2[]=$mitumoriSai;
                }
                
                $mitumoriSais = collect($mitumoriSais2);   
        }

        $sumGaku=$mitumoriSais->sum("gaku");

        foreach($mitumoriSais as &$s){
            if($s->hiyo_kbn==5){
                $s->tanka='';
                $s->su='';
                $s->tani='';
                $s->gaku=abs($s->gaku);
            }
        }
        
        $mitumori=DB::table("mitumoris")->join("folders",function($join){
                $join->on("mitumoris.groupId","=","folders.id");
            })->where("mitumoris.id",$id)
            ->select(
                "mitumoris.*",
                "mitumoris.atesaki as caseGroupName",
            )
            ->first();

        $sumRow=(object)[
                    "su"=> "",
                    "tani"=>"",
                    "gyoNo"=> null,
                    "biko"=> "",
                    "hiyo_kbn"=> "",
                    "name"=> "【合計】",
                    "siyo"=> "",
                    "tanka"=> "",
                    "gaku"=> $sumGaku,
                    "noNameSiyo"=> "【合計】",
                    "rom_gaku"=> $romgaku
                ];
        
        $taxRow=(object)[
                    "su"=> "",
                    "tani"=>"",
                    "gyoNo"=> null,
                    "biko"=> "",
                    "hiyo_kbn"=> "",
                    "name"=> "消費税"."(".$mitumori->tax_rate."%)",
                    "siyo"=> "",
                    "tanka"=> "",
                    "gaku"=> round($sumGaku*($mitumori->tax_rate/100)),
                    "noNameSiyo"=> "消費税",
                    "rom_gaku"=> $romgaku
                ];

        $sumPlusTaxRow=(object)[
                    "su"=> '',
                    "tani"=>"",
                    "gyoNo"=> null,
                    "biko"=> "",
                    "hiyo_kbn"=> "",
                    "name"=> "【総合計】",
                    "siyo"=> "",
                    "tanka"=> "",
                    "gaku"=> $sumGaku + round($sumGaku*($mitumori->tax_rate/100)),
                    "noNameSiyo"=> "【総合計】",
                    "rom_gaku"=> $romgaku
                ];
        
        //消費税や合計行を追加
        $count=$mitumoriSais->count();
        $mitumoriSais[]=$sumRow;
        $mitumoriSais[]=$taxRow;
        $mitumoriSais[]=$sumPlusTaxRow;

        $mitumoriSais=$mitumoriSais->values();

        //スペース特殊文字使えない
        foreach($mitumoriSais as &$s){
            $s->noNameSiyo=str_replace('&nbsp;',' ',$s->noNameSiyo);
        }

        //和暦
        $ymd = Date::parse($mitumori->print_at);  // 2025-07-18
        $jy=$ymd->toWareki()->format('y年n月j日');
        
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(storage_path("app/excel/見積テンプレ.xlsx"));
        //phpSpreadsheet操作
        // $spreadsheet->getDefaultStyle()->getFont()->setName('ＭＳ Ｐゴシック');
        $worksheet = $spreadsheet->getActiveSheet();
        $worksheet -> setCellValue("A2","No.".$mitumori->code);
        $worksheet->getStyle(2)->getFont()->setSize(8);
        
        $worksheet -> setCellValue("A4",$jy);
        $worksheet -> setCellValue("A5",$mitumori->atesaki);
        $worksheet -> setCellValue("E5",$mitumori->keisyo);
        $worksheet -> setCellValue("C6",$mitumori->title);
        $worksheet -> setCellValue("C7",$mitumori->area);
        $worksheet -> setCellValue("C8",$mitumori->torihikiho);
        $worksheet -> setCellValue("C9",$mitumori->kigen);
        $worksheet -> setCellValue("A11","　御見積金額 　　￥".$sumGaku + round($sumGaku*($mitumori->tax_rate/100)).".(税込)");
        $worksheet -> setCellValue("H5",$company->name);
        $worksheet -> setCellValue("H6",$company->post.' '.$company->delegate."\n〒".$company->yubin_no."\n".$company->address);
        $worksheet -> setCellValue("H8","TEL ".$company->phone."\nFAX ".$company->fax);
        $worksheet -> setCellValue("H9","担当 ".$mitumori->tantoName);
        $worksheet -> setCellValue("B40",$mitumori->biko);

        //会社印鑑＝＝＝＝＝＝＝＝＝＝＝＝＝＝
        if(file_exists(public_path('stamp_img/companystamp.png'))){
            $drawing = new Drawing();
            $drawing->setName('Sample Image');
            $drawing->setDescription('Sample Image');
            $drawing->setPath(public_path('stamp_img/companystamp.png')); // 画像のパスを指定
            $drawing->setHeight(55); // 高さを80pxに設定
            $drawing->setOffsetX(40);
            $drawing->setCoordinates('I5'); // 画像を配置するセル
            $drawing->setWorksheet($worksheet);
        }

        //担当者印鑑＝＝＝＝＝＝＝＝＝＝＝＝＝
        $PIC = DB::table('users')->where('name','=',$mitumori->tantoName)->first();
        $PIC_id=$PIC?$PIC->id:0;
        if(file_exists(public_path('stamp_img/'.$PIC_id.'.png'))){
            $drawing2 = new Drawing();
            $drawing2->setName('Sample Image');
            $drawing2->setDescription('Sample Image');
            $drawing2->setPath(public_path('stamp_img/'.$PIC_id.'.png')); // 画像のパスを指定
            $drawing2->setHeight(40); // 高さを80pxに設定
            $drawing2->setOffsetX(30);
            $drawing2->setOffsetY(10);
            $drawing2->setCoordinates('I12'); // 画像を配置するセル
            $drawing2->setWorksheet($worksheet);
        };

    

        for($i=1;$i<=23;$i++){
            if(!isset($mitumoriSais[$i-1])) break;
            $rowNum=16+$i-1;
            $worksheet -> setCellValue("A".$rowNum,$mitumoriSais[$i-1]->noNameSiyo);

            //=============================================================
            //文字数オーバーなら文字小さくして折り返すように上書き
            $nameLength = mb_strwidth($mitumoriSais[$i-1]->noNameSiyo,'UTF-8')/2;
            if($nameLength > 29 && $mitumoriSais[$i-1]->hiyo_kbn==0){
                $worksheet -> setCellValue("A".$rowNum,$mitumoriSais[$i-1]->brLineName);
                $worksheet -> getStyle("A".$rowNum)->applyFromArray([
                                'alignment' => [
                                    'vertical' => Alignment::VERTICAL_CENTER,
                                    'wrapText' => true,
                                ],
                                'font' => [
                                    'size' => 7,
                                ],
                            ]);
            }
            //===============================================================

            $worksheet -> setCellValue("D".$rowNum,$mitumoriSais[$i-1]->su);
            $worksheet -> setCellValue("E".$rowNum,$mitumoriSais[$i-1]->tani);
            
            if($mitumoriSais[$i-1]->hiyo_kbn != ""){//合計　消費税　総合計は単価を表示しない
                $worksheet -> setCellValue("F".$rowNum,$mitumoriSais[$i-1]->tanka);
                $worksheet -> getStyle("F".$rowNum)->getNumberFormat()->setFormatCode('#,##0');
            }
            $worksheet -> setCellValue("H".$rowNum,$mitumoriSais[$i-1]->gaku);
            $worksheet -> getStyle("H".$rowNum)->getNumberFormat()->setFormatCode('#,##0');
            $worksheet -> setCellValue("I".$rowNum,$mitumoriSais[$i-1]->biko);
        }

        if($mitumoriSais->count()<=23){
            $totalPage = 1;
        }else{
            $totalPage = 1+floor($mitumoriSais->slice(23)->count()/41)+($mitumoriSais->slice(23)->count() % 41 > 0 ? 1 : 0);
        }
        $worksheet -> setCellValue("I2",'Page 1/'.$totalPage);
                
        //2page目以降の処理
        if($mitumoriSais->count()>23) $worksheet=$this->excel2pageOver($worksheet,2,$totalPage,$mitumoriSais->slice(23),$mitumori->code);            
                
        //操作終了
        $spreadsheet=new XlsxWriter($spreadsheet);
        $spreadsheet->save(storage_path("app/excel/御見積.xlsx"));
        return response()->download(storage_path("app/excel/御見積.xlsx"),"御見積書_".$mitumori->atesaki."御中_".$jy.".xlsx");
    }


    //2pege以上ある場合の処理 (再起呼び出し)
    private function excel2pageOver($worksheet,$page,$totalPage,$data,$code){

        $startRow=43+($page-2)*44;
        $worksheet->setCellValue("A".$startRow,'No.'.$code);
        $worksheet->getStyle($startRow)->getFont()->setSize(8);
        $worksheet->setCellValue("I".$startRow,'Page '.$page.'/'.$totalPage);
        $worksheet->getStyle("I".$startRow)-> getAlignment() -> setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $headerRow=$startRow+2;
        
        for($i=0;$i<=41;$i++){
            $worksheet->mergeCells('A'.($headerRow+$i).':C'.($headerRow+$i));
            $worksheet->mergeCells('F'.($headerRow+$i).':G'.($headerRow+$i));
            $worksheet->getRowDimension($headerRow+$i)->setRowHeight(0.64,"cm");
        }

        $worksheet->getStyle('A'.$headerRow.':I'.$headerRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('D9D9D9');
        $worksheet->getStyle('A'.$headerRow.':I'.($headerRow+41))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $worksheet->getStyle('A'.$headerRow.':I'.$headerRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('A'.$headerRow.':I'.$headerRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $worksheet->getStyle('A'.$headerRow.':I'.($headerRow+41))->getFont()->setSize(8);
        // $worksheet->getStyle('A'.$headerRow.':I'.($headerRow+41))->getFont()->getDefaultStyle()->getFont()->setName('ＭＳ Ｐゴシック');
        $worksheet->setCellValue('A'.$headerRow,'品名');
        $worksheet->setCellValue('D'.$headerRow,'数量');
        $worksheet->setCellValue('E'.$headerRow,'単位');
        $worksheet->setCellValue('F'.$headerRow,'単価');
        $worksheet->setCellValue('H'.$headerRow,'金額');
        $worksheet->setCellValue('I'.$headerRow,'備考');

        $take41=$data->take(41);
        
        $i=1;
        foreach($take41 as $key=>$sai){
            $rowNum=$headerRow+$i;
            $worksheet -> setCellValue("A".$rowNum,$sai->noNameSiyo);

             //文字数オーバーなら文字小さくして折り返すように上書き
            $nameLength = mb_strwidth($sai->noNameSiyo,'UTF-8')/2;
            if($nameLength > 29 && $sai->hiyo_kbn==0){
                $worksheet -> setCellValue("A".$rowNum,$sai->brLineName);
                $worksheet -> getStyle("A".$rowNum)->applyFromArray([
                                'alignment' => [
                                    'vertical' => Alignment::VERTICAL_CENTER,
                                    'wrapText' => true,
                                ],
                                'font' => [
                                    'size' => 7,
                                ],
                            ]);
            }
            //===============================================================

            $worksheet -> setCellValue("D".$rowNum,$sai->su);
            $worksheet -> setCellValue("E".$rowNum,$sai->tani);
            if($sai->hiyo_kbn != ""){//合計　消費税　総合計は単価を表示しない
                $worksheet -> setCellValue("F".$rowNum,(int)$sai->tanka);
                $worksheet -> getStyle("F".$rowNum)->getNumberFormat()->setFormatCode('#,##0');
            }
            $worksheet -> setCellValue("H".$rowNum,(int)$sai->gaku);
            $worksheet -> getStyle("H".$rowNum)->getNumberFormat()->setFormatCode('#,##0');
            $worksheet -> getStyle("F".$rowNum)-> getAlignment() -> setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $worksheet -> getStyle("H".$rowNum)-> getAlignment() -> setHorizontal(Alignment::HORIZONTAL_RIGHT);
            
            $worksheet -> setCellValue("I".$rowNum,$sai->biko);
            $i++;
        }

        $data=$data->slice(41);
        
        if($data->count()>=1) $worksheet=$this->excel2pageOver($worksheet,$page+1,$totalPage,$data,$code);
        return $worksheet;
    }

    public function romUpdate($id, Request $request){
        //電気労務単価
        if($request->rom_tanka_type == "de"){
            
            $sumBugakari=DB::table("mitumoriSais")
            ->where("mitumoriId",$id)
            ->sum("bugakariDe");

            
            //労務単価の更新
            DB::table("mitumoris")->where("id",$id)->update([
                "rom_tankaDe"=>$request->rom_tankaDe,
                "rom_gakuDe"=>$request->rom_tankaDe*$sumBugakari
            ]);

        //普通労務単価
        }elseif($request->rom_tanka_type == "hu"){

            $sumBugakari=DB::table("mitumoriSais")
            ->where("mitumoriId",$id)
            ->sum("bugakari");

            //労務単価の更新
            DB::table("mitumoris")->where("id",$id)->update([
                "rom_tanka"=>$request->rom_tanka,
                "rom_gaku"=>$request->rom_tanka*$sumBugakari
            ]);

        //特殊労務単価
        }elseif($request->rom_tanka_type == "to"){
            
            $sumBugakari=DB::table("mitumoriSais")
            ->where("mitumoriId",$id)
            ->sum("bugakariTo");
            
            //労務単価の更新
            DB::table("mitumoris")->where("id",$id)->update([
                "rom_tankaTo"=>$request->rom_tankaTo,
                "rom_gakuTo"=>$request->rom_tankaTo*$sumBugakari
            ]);
        }

        return \redirect()->back();
    }


    public function aimitu(Request $request,$id,$companyId){

        //再計算または値が何もない時は通過
        if(
            isset($request->reStore) ||
            !DB::table("mitumoris")->where("mitumoriId",$id)->where("companyId",$companyId)->exists()
        ){
            $this->aimituStore($id,$companyId);
        }

        //相見積もりの元となる見積
        $mitumori=DB::table("mitumoris")
        ->where("mitumoriId",$id)
        ->where("companyId",$companyId)
        ->first();

        
        //相見積もりの元となる見積
        $mitumoriSais=DB::table("mitumoriSais")
        ->where("mitumoriId",$mitumori->id)
        ->orderBy("hiyo_kbn","asc")
        ->orderBy("gyoNo","asc")
        ->orderBy("sizaiCode","asc")
        // ->orderBy("gyoNo is null asc")
        // ->orderBy("gyoNo","asc")
        // ->orderByRaw("name = '消耗品雑材' desc")
        // ->orderBy("kansetu_code","asc")
        ->get();

        $totalGaku=DB::table("mitumoriSais")
        ->where("mitumoriId",$mitumori->id)
        ->selectRaw("sum(su*tanka) as gaku")
        ->groupBy("mitumoriId")
        ->first()->gaku;

        //値が変わっているので取り直し
        $mitumori=DB::table("mitumoris")
        ->where("mitumoriId",$id)
        ->where("companyId",$companyId)
        ->first();

        $totalGaku+=$mitumori->rom_gaku;

        return view("mitumoriKani.aimitu",[
            "mitumori"=>$mitumori,
            "totalGaku"=>$totalGaku,
            "mitumoriSais"=>$mitumoriSais
        ]);

    }


    private function aimituStore($id,$companyId){
        
        //削除処理＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝
            $oldAimituId=DB::table("mitumoris")
                ->where("mitumoriId",$id)
                ->where("companyId",$companyId)
                ->first()->id??null;
            
            if($oldAimituId<>null){
                //相みつの細目を削除
                DB::table("mitumoriSais")
                ->where("mitumoriId",$oldAimituId)
                ->delete();

                //相みつを削除
                DB::table("mitumoris")
                ->where("id",$oldAimituId)
                ->delete();

                 //間接費計算テーブルを消す
                DB::table("kansetu_calculations")
                ->where("mitumoriId",$oldAimituId)
                ->delete();
            }
        //＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝
        $data=Mitumori::find($id);
        $newData=$data->replicate();
        $newData->companyId=$companyId;
        $newData->mitumoriId=$id;
        $newData->aimitu_flg=1;
        $newData->tantoName='';
        
        if($newData->hukugo_tanka_flg==0){
            $newData->rom_gaku=Mitumori::WithGaku($id)->first()->romGaku??0;
        }else{
            //複合単価ならば
            $newData->rom_gaku=0;
        }
        
        $newData->rom_gakuDe=0;
        $newData->rom_gakuTo=0;
        $newData->save();

        $company=DB::table("companies")->where("id",$companyId)->first();
        $rateArray=[];
        $at_first=$company->mokuhyo_rate-$company->hendo_rate;
        $at_end=$company->mokuhyo_rate+$company->hendo_rate;
        for($at_first; $at_first<=$at_end; $at_first+=0.01){
            $rateArray[]=$at_first;
        }
        $mitumoriSais=MitumoriSai::where("mitumoriId",$data->id)->get();
        foreach($mitumoriSais as $mitumoriSai){
            $newSai=$mitumoriSai->replicate();
            $newSai->mitumoriId=$newData->id;

            if($newSai->hiyo_kbn==3 && $newData->kansetu_auto_calculate==1){
                //間接費自動計算の場合は、間接費細目は自動計算単価をコピーする
                $newSai->tanka=$newSai->auto_tanka;
            }

            if($newData->hukugo_tanka_flg==0){
                $newSai->moto_tanka=$newSai->tanka;
            }else{
                //複合単価ならば
                $rom=round($newSai->tekkyo_rate*$newSai->bugakariDe*$newData->rom_tankaDe)+round($newSai->tekkyo_rate*$newSai->bugakari*$newData->rom_tanka)+round($newSai->tekkyo_rate*$newSai->bugakariTo*$newData->rom_tankaTo);
                $newSai->moto_tanka=$newSai->tanka+$rom;
            }
            $newSai->tanka=round($newSai->moto_tanka*$rateArray[array_rand($rateArray)]);
            //相見積もりは歩掛は使わない、直接労務額を更新する
            $newSai->bugakariDe=0;
            $newSai->bugakari=0;
            $newSai->bugakariTo=0;
            $newSai->save();
        }

        //見積細目の移行が終わったら自動計算はオフにする
        //相見積もりに自動計算機能はないため
        $newData->kansetu_auto_calculate=0;
        $newData->save();
        //＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝


        //同じ資材は同じ単価にしないといけないのでそろえる。
        $tanka_rendo_code='none';
        $tanka=0;
        MitumoriSai::where("mitumoriId",$newData->id)
        ->where("tanka_rendo_code","!=","")
        ->orderBy("tanka_rendo_code")
        ->each(function($q)use(&$tanka_rendo_code,&$tanka){
            if($tanka_rendo_code==$q->tanka_rendo_code){
                $q->tanka=$tanka;
                $q->save();
            }else{
               $tanka=$q->tanka;
               $tanka_rendo_code=$q->tanka_rendo_code;
            }
        });

    }
    public function focusReki(Request $request){
        // dd($request);
        $this->RekiService->rekiStore($request->id,"past");
        return response()->json([
            "status"=>200,
            "message"=>"履歴保存済"
        ]);
    }

     public function blurReki(Request $request){
        $reki=DB::table("mitumoriRekis")
        ->where("kakutei_flg",0)
        ->where("reki_url",$_SERVER['HTTP_REFERER'])
        ->orderBy("reki_no","desc")
        ->first();
        $rekiNo=isset($reki)?$reki->reki_no:0;
        
        $id=$request->id;

        $mitumori=(array)DB::table("mitumoris")
        ->where("id",$id)
        ->select("kansetu_auto_calculate","rom_tanka","rom_tankaDe","rom_tankaTo","rom_gaku")
        ->get()->toArray();
        $mitumoriReki=(array)DB::table("mitumoriRekis")
        ->where("reki_url",$_SERVER['HTTP_REFERER'])
        ->where("reki_no",$rekiNo)
        ->where("past_or_future",'past')
        ->select("kansetu_auto_calculate","rom_tanka","rom_tankaDe","rom_tankaTo","rom_gaku")
        ->get()->toArray();
        
        $jsonSai=(array)DB::table("mitumoriSais")
        ->where("mitumoriId",$id)
        ->get()->toArray();
        $jsonRekiSai=(array)DB::table("mitumoriSaiRekis")
        ->where("reki_url",$_SERVER['HTTP_REFERER'])
        ->where("reki_no",$rekiNo)
        ->where("mitumoriId",$id)
        ->where("past_or_future",'past')
        ->select(DB::getSchemaBuilder()->getColumnListing("mitumoriSais"))
        ->get()->toArray();
        
        //タイムスタンプの誤差は無視
        // foreach($mitumori as &$data){ unset($data->created_at, $data->updated_at); }
        // foreach($mitumoriReki as &$data){ unset($data->created_at, $data->updated_at); }
        foreach($jsonSai as &$data){ unset($data->created_at, $data->updated_at); }
        foreach($jsonRekiSai as &$data){ unset($data->created_at, $data->updated_at); }

        //現在のデータに誤差があれば削除
        if($mitumori!=$mitumoriReki || $jsonSai!=$jsonRekiSai){
            DB::table("mitumoriRekis")
            ->where("reki_url",$_SERVER['HTTP_REFERER'])
            ->where("reki_no",$rekiNo)
            ->update(["kakutei_flg"=>1]);

            //未来が変わったため未来の履歴を消す
            $this->RekiService->rekiDelete("future");
        }else{
            DB::table("mitumoriRekis")->where("past_or_future",'past')->where("reki_no",$rekiNo)->where("reki_url",$_SERVER['HTTP_REFERER'])->delete();
            DB::table("mitumoriSaiRekis")->where("past_or_future",'past')->where("reki_no",$rekiNo)->where("reki_url",$_SERVER['HTTP_REFERER'])->delete();
        }

        return response()->json([
            "status"=>200,
            "message"=>"blurReki履歴保存済"
        ]);
    }

    public function go_to_the_past($id){


        if(!DB::table("mitumoriRekis")->where("kakutei_flg",1)->where("past_or_future","past")->where("reki_url",$_SERVER['HTTP_REFERER'])->exists()){
            return \redirect()->back();
        }

        //現在を未来に送る
        $this->RekiService->rekiStore($id,"future",1);
        
        //dataの入れ替え
        list($reki,$rekiKos,$rekiSais)=$this->RekiService->topRekiGet('past');
        $rekiSais=(array)json_decode(json_encode($rekiSais->unique('id')->toArray()),true);
        
        DB::table("mitumoriSais")->where('mitumoriId',$id)->delete();
        DB::table("mitumoriSais")->insert($rekiSais);
        DB::table("mitumoris")
        ->where("id",$reki->id)
        ->update([
            "kansetu_auto_calculate"=>$reki->kansetu_auto_calculate,
            "rom_tanka"=>$reki->rom_tanka,
            "rom_tankaDe"=>$reki->rom_tankaDe,
            "rom_tankaTo"=>$reki->rom_tankaTo,
            "rom_gaku"=>$reki->rom_gaku
        ]);

        //過去を現在に移したので過去のデータは消す
        $this->RekiService->topRekiDelete("past");
        $this->RekiService->sizaiToMatch($id);

        return back();
    }

    public function go_to_the_future($id){

       if(!DB::table("mitumoriRekis")->where("past_or_future","future")->where("reki_url",$_SERVER['HTTP_REFERER'])->exists()){
            return \redirect()->back();
        }

        //現在を過去に送る
        $this->RekiService->rekiStore($id,"past",1);
        
        //dataの入れ替え
        list($reki,$rekiKos,$rekiSais)=$this->RekiService->topRekiGet('future');
        $rekiSais=(array)json_decode(json_encode($rekiSais->toArray()),true);

        DB::table("mitumoriSais")->where('mitumoriId',$id)->delete();
        DB::table("mitumoriSais")->insert($rekiSais);
        DB::table("mitumoris")
        ->where("id",$reki->id)
        ->update([
            "kansetu_auto_calculate"=>$reki->kansetu_auto_calculate,
            "rom_tanka"=>$reki->rom_tanka,
            "rom_tankaDe"=>$reki->rom_tankaDe,
            "rom_tankaTo"=>$reki->rom_tankaTo,
            "rom_gaku"=>$reki->rom_gaku
        ]);

        //過去を現在に移したので過去のデータは消す
        $this->RekiService->topRekiDelete("future");
        $this->RekiService->sizaiToMatch($id);
        
        return back();
        
    }

    public function checkReki(Request $request){
        list($future,$past)=$this->RekiService->checkReki();
        return response()->json([
            "status"=>200,
            "future"=>$future,
            "past"=>$past
        ]);
        

    }


}
