<?php

namespace App\Http\Controllers;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use Auth;
use Carbon\Carbon;

use App\s_anken;

class ScheduleController extends Controller
{
    
    
    
   
    public function index(Request $request)
    {
        
        
    
        
        $dapas=DB::table('departments')->get();

        // dd($request->all());
        
        //来月ボタン
        if(isset($request->addMonth)){
            $ym=carbon::parse($request->addMonth);
            $copyArray=['title'=>$request->copyTitle,'contents'=>$request->copyContents,'color'=>$request->copyColor,'address'=>$request->copyAddress];
        
        //先月ボタン    
        }elseif(isset($request->subMonth)){
            $ym=carbon::parse($request->subMonth);
            $copyArray=['title'=>$request->copyTitle,'contents'=>$request->copyContents,'color'=>$request->copyColor,'address'=>$request->copyAddress];
        
        //社外ユーザ追加
        }elseif(isset($request->addOutUser)){
            $now=carbon::now();
            // $authId=auth::user()->id;
            $ym=carbon::parse($request->target_ym);
            $copyArray=['title'=>'','contents'=>'','color'=>'','address'=>''];
            $request->merge(['selectDepa' =>$request->outUserDepa]);
            DB::table("out_users")->insert([
                'name'=>$request->outUserName,
                'depa_id'=>$request->outUserDepa,
                'target_at'=>$ym
                ]);
        //社外ユーザ更新    
        }elseif(isset($request->updateOutUser)){
            
            // dd($request);
            $now=carbon::now();
            // $authId=auth::user()->id;
            $ym=carbon::parse($request->target_ym);
            $copyArray=['title'=>'','contents'=>'','color'=>'','address'=>''];
            $request->merge(['selectDepa' =>$request->outUserDepa]);
            DB::table("out_users")->where("id",$request->outUserEditId)->update([
                'name'=>$request->outUserName,
                'depa_id'=>$request->outUserDepa,
                
                ]);
            
         //社外ユーザ削除  
        }elseif(isset($request->deleteOutUser)){
            
            $now=carbon::now();
            // $authId=auth::user()->id;
            $ym=carbon::parse($request->target_ym);
            $copyArray=['title'=>'','contents'=>'','color'=>'','address'=>''];
            $request->merge(['selectDepa' =>$request->outUserDepa]);
            DB::table("out_users")->where("id",$request->outUserEditId)->delete();
            DB::table("s_events")->where("user_id",'out'.$request->outUserEditId)->delete();
        
       
        }else{
            $ym=carbon::today();
            $copyArray=['title'=>'','contents'=>'','color'=>'','address'=>''];
       }
    //   dd($copyArray);
       
       //スクロール位置
       if($ym->copy()->format("Y-m")==carbon::today()->copy()->format("Y-m")){
           $scrollPivot=(carbon::today()->day-1)*90;
           $today=carbon::today()->day;
       }else{
           $scrollPivot=0;
           $today='';
       }
        
        
        $startYmd=$ym->copy()->startOfMonth();
        $endYmd=$ym->copy()->endOfMonth();
        $endDay=$ym->copy()->endOfMonth()->format('d');
        
        
        $holi=DB::table("kintai_holidays")
            ->whereBetWeen("ymd",[$startYmd,$endYmd])
            ->select(DB::raw("day(ymd) as ymdday"))
            ->get();
        
        $events=json_encode(DB::table('s_events')
                // ->leftjoin("s_ankens","s_ankens.id","=","s_events.title_id")
                // ->whereNull("s_ankens.deleted_at")
                ->whereBetWeen("target_at",[$startYmd,$endYmd])
                ->select(
                    "s_events.id",
                    // "s_events.contents",
                    DB::raw("case when contents != '' then contents else '　' end as contents"),
                    DB::raw("concat('.event-',user_id,'-',target_at) as event"),
                    DB::raw("title as name"),
                    DB::raw("s_events.color as color")
                    )->get());
                
        
        $notUserId=[157];
        $leave_requests=json_encode(DB::table('leave_requests')
                ->whereBetWeen("request_at",[$startYmd,$endYmd])
                ->whereNotIn("user_id",$notUserId)
                ->whereIn("purpose_kbn",[1,2,3,4,6,7,8,9])
                ->select(
                    DB::raw("concat('leave-',id) as id"),
                    DB::raw("concat('.event-',user_id,'-',date_format(request_at,'%Y-%m-%d')) as event"),
                    DB::raw("case purpose_kbn
                        when 1 then '有給休暇'
                        when 2 then '午前休'
                        when 3 then '午後休'
                        when 4 then '振替休日'
                        when 6 then '欠勤'
                        when 7 then '遅刻'
                        when 8 then '早退'
                        when 9 then concat('時間休',FLOOR(uqH),'H')
                        end as name"),
                    DB::raw("case when reason != '' then reason else '　' end as contents")
                    )->get());
                    
        // dd($events);
                    
        // dd($leave_requests);
        
        
        $outUsers=DB::table("out_users")
        ->where(DB::raw("year(target_at)"),$ym->copy()->format('Y'))
        ->where(DB::raw("month(target_at)"),$ym->copy()->format('m'))
        ->select(
            DB::raw("concat('out',id) as id"),
            "name",
            "depa_id"
            );
                    
         $users=DB::table('user_department')
                  ->join('gwusers','gwusers.id','=','user_department.user_id')
                  ->where('user_department.department_id','<>',59)
                  ->select(
                        'gwusers.id',
                        DB::raw("REPLACE(REPLACE(gwusers.name,'　',''),' ','') as name"),
                        'user_department.department_id as depa_id')
                  ->orderBy('user_department.department_id','asc')
                  ->orderBy('gwusers.id','asc')
                  ->groupBy('gwusers.id',"gwusers.name","user_department.department_id");
                   
        $users=DB::table(DB::raw("({$users->toSql()}) as users"))
        ->setBindings($users->getBindings())
        ->unionAll($outUsers)->get();

                  
            
        
        
        //複数部署に所属している人がいるため、配列としてもつ
        $depa=DB::table('user_department')->join('gwusers','gwusers.id','=','user_department.user_id')
                  ->select("user_id",DB::raw("concat('depa-',department_id) as depa"))->get();
        $depaArray=[];
        foreach($depa as $d){
            if(isset($depaArray[$d->user_id])){
                $depaArray[$d->user_id]=$depaArray[$d->user_id].' '.$d->depa;    
            }else{
                $depaArray[$d->user_id]=$d->depa;    
            }
        }
        
        //外部userは複数所属できないが同じように追加
        $outUserGeted=$outUsers->get();
        foreach($outUserGeted as $d){
                $depaArray[$d->id]='depa-'.$d->depa_id;    
        }
        
        // dd($depaArray);
        
        
        
        
        // if(DB::table('user_department')->where('user_id',auth::user()->id)->exists()){
        //     $myDepaId=DB::table('user_department')->where('user_id',auth::user()->id)->first()->department_id;
        // }else{
            $myDepaId='all';
        // }
        
        
        $selectdepa=isset($request->selectDepa)?$request->selectDepa:$myDepaId;
        
        if(isset($request->selectDepa)){
            $selectdepa=$request->selectDepa;
        }else{
            $selectdepa=$myDepaId==58?'all':$selectdepa;
        }
        
        
        $depaIdArray=array_column(DB::table('user_department')->get()->toArray(),'department_id');
        $depaQuery=DB::table('s_ankens');
        if($myDepaId!=58){
            $depaQuery=$depaQuery->whereIn('depa_id',$depaIdArray);
        }
        $depaQuery=$depaQuery->get();
        
        
        $dayArray=[];
        for($i=1; $i<=$endDay; $i++){
            $dayArray[]=$i;    
        }
        
        $users=json_decode(json_encode($users),true);
        
        foreach($users as &$u){
            for($i=1; $i<=$endDay; $i++){
                $u["day".$i]=$i;
            }
        }
        
        
        // if(DB::table('diligences_sime')->orderBy("sime_ymd",'desc')->first()->sime_ymd >= $ym->copy()->endOfMonth()->format("Y-m-d")){
        //     $yukyuControl=false;
        // }else{
            $yukyuControl=true;
        // }
        
        
        
        return view('schedule.index2',[
            // 'users'=>json_encode(DB::table('users')->select('name','id')->take(10)->get()),
            // 'users'=>json_encode($array1),
            'copyArray'=>$copyArray,
            'yukyuControl'=>$yukyuControl,
            'today'=>$today,
            'pivot'=>$scrollPivot,
            'depaArray'=>$depaArray,
            'holis'=>array_column($holi->toArray(),"ymdday"),
            'ankens'=>$depaQuery,
            'depas'=>DB::table('departments')->where('id','<>',59)->orderBy("id")->get(),
            'users'=>$users,
            'outUsers'=>$outUsers,
            'endDay'=>$endDay,
            'dayArray'=>$dayArray,
            'events'=>$events,
            'leave_requests'=>$leave_requests,
            'dateYM'=>$ym->copy()->format('Y-m'),
            'dayOfWeek'=>['日','月','火','水','木','金','土'],
            'weekNumber'=>$ym->copy()->startOfMonth()->dayOfWeek,
            'ym'=>$ym,
            'selectdepa'=>$selectdepa
            ]);
    }
    
    public function printing($ym,$depa){
        
       
        
        
        
        $ym=carbon::parse($ym);
        $startYmd=$ym->copy()->startOfMonth();
        $endYmd=$ym->copy()->endOfMonth();
        $endDay=$ym->copy()->endOfMonth()->format('d');
        
        $outUsers=DB::table("out_users")
        ->where(DB::raw("year(target_at)"),$ym->copy()->format('Y'))
        ->where(DB::raw("month(target_at)"),$ym->copy()->format('m'))
        ->select(
            DB::raw("concat('out',id) as id"),
            "name",
            "depa_id"
            );
            
                    
                    
        $users=DB::table('user_department')->join('gwusers','gwusers.id','=','user_department.user_id');
        
        if($depa!="all"){
            $users->where('user_department.department_id',$depa);
            $outUsers->where('depa_id',$depa);
            $depaName=DB::table('departments')->where('departments.id',$depa)->first()->name;
        }else{
            $depaName="";
        }
                
        $users->select(
                'gwusers.id',
                DB::raw("REPLACE(REPLACE(gwusers.name,'　',''),' ','') as name"),
                'user_department.department_id as depa_id')
            ->orderBy('user_department.department_id','asc')
            ->orderBy('gwusers.id','asc')
            ->groupBy('gwusers.id','gwusers.name','user_department.department_id');
                   
        $users=DB::table(DB::raw("({$users->toSql()}) as users"))
        ->setBindings($users->getBindings())
        ->unionAll($outUsers)->get();
        
        $holi=DB::table("kintai_holidays")
            ->whereBetWeen("ymd",[$startYmd,$endYmd])
            ->select(DB::raw("day(ymd) as ymdday"))
            ->get();
        
        $events=json_encode(DB::table('s_events')
                // ->leftjoin("s_ankens","s_ankens.id","=","s_events.title_id")
                // ->whereNull("s_ankens.deleted_at")
                ->whereBetWeen("target_at",[$startYmd,$endYmd])
                ->select(
                    "s_events.id",
                    // "s_events.contents",
                    DB::raw("case when contents != '' then contents else '　' end as contents"),
                    DB::raw("concat('.event-',user_id,'-',target_at) as event"),
                    DB::raw("title as name"),
                    DB::raw("s_events.color as color")
                    )->get());
                
                    
        $leave_requests=json_encode(DB::table('leave_requests')
                ->whereBetWeen("request_at",[$startYmd,$endYmd])
                ->whereIn("purpose_kbn",[1,2,3,4,6,7,8,9])
                ->select(
                    DB::raw("concat('leave-',id) as id"),
                    DB::raw("concat('.event-',user_id,'-',date_format(request_at,'%Y-%m-%d')) as event"),
                    DB::raw("case purpose_kbn
                        when 1 then '有給休暇'
                        when 2 then '午前休'
                        when 3 then '午後休'
                        when 4 then '振替休日'
                        when 6 then '欠勤'
                        when 7 then '遅刻'
                        when 8 then '早退'
                        when 9 then concat('時間休',FLOOR(uqH),'H')
                        end as name"),
                    DB::raw("case when reason != '' then reason else '　' end as contents")
                    )->get());
        
        $users=json_decode(json_encode($users),true);
        
        foreach($users as &$u){
            for($i=1; $i<=$endDay; $i++){
                $u["day".$i]=$i;
            }
        }
        
        
         return view('schedule.schedulePrintB4',[
            'users'=>$users,
            'dateYM'=>$ym->copy()->format('Y-m'),
            'endDay'=>$endDay,
            'ym'=>$ym,
            'events'=>$events,
            'leave_requests'=>$leave_requests,
            'holis'=>array_column($holi->toArray(),"ymdday"),
            'weekNumber'=>$ym->copy()->startOfMonth()->dayOfWeek,
            'dayOfWeek'=>['日','月','火','水','木','金','土'],
            "depaName"=>$depaName
             ]);
    }

    
    public function insert(Request $request)
    {
        // dd($request->selectAnken,$request->eventContents);
        
       
        
        $now=carbon::now();
        $insert=[];
        $ids=[];
        
        
        
        if($request->name == "振替休日" || $request->name == "有給休暇"  || $request->name == "午前休"){
            
            $data=$request->arrayData[0];
            $updatedContents=explode("-",$data);
            $userId=$updatedContents[1];
            $ymd=$updatedContents[2].'-'.$updatedContents[3].'-'.$updatedContents[4];
            
            switch($request->name){
                case"有給休暇":
                    $kbn=1;
                    $uqH=7;
                    break;
                case"振替休日":
                    $kbn=4;
                    $uqH=0;
                    break;
            }
            
            
            
            //有給残        
            if(strpos($userId,"out")===0){
                return response()->json(['message'=>'社外ユーザには申請出来ません。']);
            
            //もうある時(複数画面表示していて重複があるとき、重複したidを返す。)
            }elseif(DB::table('leave_requests')->where('user_id',$userId)->where('purpose_kbn',$kbn)->where('request_at',$ymd)->exists()){
                $ids[]="leave-".DB::table('leave_requests')->where('user_id',$userId)->where('purpose_kbn',$kbn)->where('request_at',$ymd)->first()->id;
                return response()->json($ids);
            
            //有給残がある時、有給残を減らす。    
            }elseif(in_array($kbn,[1,2,3])){
                // $paid=DB::table('paid_leaves')
                // ->where('user_id',$userId)
                // ->where('zan_h','>=',$uqH)
                // ->where('kigen','>=',$ymd)
                // ->where('huyo_ymd','<=',$ymd)
                // ->where('kigen','>=',carbon::today())
                // ->orderBy('id','asc')
                // ->take(1);
            
                // $paid_id=$paid->first()->id;
                // $paid->update(['zan_h'=>DB::raw("zan_h-'$uqH'")]);
            }
            
            
            $insert["purpose_kbn"]= $kbn;
            $insert["uqH"]= $uqH;
            $insert["user_id"]=$userId;
            $insert["request_at"]=$ymd;
            // $insert["paid_id"]=$paid_id;
            $insert["created_at"]=$now;
            $insert["updated_at"]=$now;
            $id=DB::table("leave_requests")->insertGetId($insert);
            $ids[]="leave-".$id;
            
            
            //勤怠データが存在しなければ入れるでないと最初表示されない==========================================================
            if(!DB::table('diligences')->where('user_id',$userId)->where('target_at',$ymd)->exists()){
                // DB::table('diligences')->insert(['user_id'=>$userId,'target_at'=>$ymd]);
            }//=================================================================================================================
            
            
            
            
            //申請ルートと結合して承認先のユーザーごとデータ（リクエストチェックテーブル）を作成
            $insertData=DB::table('leave_routes')
                            ->where('request_user',$userId)
                            ->select(
                                "leave_routes.number",
                                DB::raw(" '".str_replace("leave-","",$ids[0])."' as leave_id"),
                                "leave_routes.request_user",
                                "leave_routes.check_user",
                                DB::raw("0 as check_kbn"),
                                DB::raw("'$now' as created_at"),
                                DB::raw("'$now' as updated_at")
                                )->get();
                                
            //route code...
            DB::table('leave_checks')->insert(json_decode(json_encode($insertData),true));
            
            //承認者がいないときは承認済にする
            if(empty($insertData)){
                DB::table('leave_requests')->where('id',$id)->update(['syonin_kbn'=>1]);
            }
            
            return response()->json($ids);
        }
        
        
       
        //普通のイベント
        foreach($request->arrayData as $key=>$data){
                
                $updatedContents=explode("-",$data);
                $userId=$updatedContents[1];
                $ymd=$updatedContents[2].'-'.$updatedContents[3].'-'.$updatedContents[4];
    
                $insert["title"]=(string)$request->name;
                $insert["color"]=$request->color;
                $insert["contents"]=(string)$request->eventContents;
                $insert["user_id"]=$userId;      
                $insert["title_id"]=0;      
                $insert["target_at"]=$ymd;
                $insert["address"]=(string)$request->address;
                $insert["created_at"]=$now;
                $insert["updated_at"]=$now;
                
                $ids[]=DB::table("s_events")->insertGetId($insert);
            
        }
        
        return response()->json($ids);
        
    }
    
    public function get(Request $request)
    {
        $contents=json_encode(DB::table("s_events")
        ->leftjoin("s_ankens","s_ankens.id","=","s_events.title_id")
        ->where('s_events.id',$request->dataId)
        ->select(
            "s_events.id",
            "s_events.contents",
            "s_events.address",
            DB::raw("concat('.event-',user_id,'-',target_at) as event"),
            DB::raw("case when title_id = 0 then title else s_ankens.name end as title"),
            DB::raw("case when title_id = 0 then s_events.color else s_ankens.color end as color")
            )
        ->first());
        
        return response()->json($contents);
        
    }

    
    public function destroy(Request $request)
    {
        
        
        //有給おｒ　振休
        if(substr($request->dataId,0,5)=="leave"){
            $id=(int)str_replace("leave-","",$request->dataId); 
            
            //チェック内容を消す
            DB::table('leave_checks')->where("leave_id",$id)->delete();
            $target=DB::table("leave_requests")
                ->where('id',$id)
                ->first();
                
            if($target->purpose_kbn==1){
                //有給残を元に戻す
                DB::table('paid_leaves')
                        ->where('id',$target->paid_id)
                        ->update(['zan_h'=>DB::raw('zan_h+7')]);
            }
            DB::table("leave_requests")
                ->where('id',$id)->delete();
            
        }else{
            DB::table("s_events")
            ->where('id',$request->dataId)
            ->delete();
        }
        
    }

    
    public function move(Request $request)
    {
        $now=carbon::now();
        $updatedContents=explode("-",$request->updatedContents);
        $userId=$updatedContents[1];
        $ymd=$updatedContents[2].'-'.$updatedContents[3].'-'.$updatedContents[4];
        
        DB::table("s_events")
        ->where('id',$request->dataId)
        ->update([
            'target_at'=>carbon::parse($ymd)->format('Y-m-d'),
            'user_id'=>$userId,
            'updated_at'=>$now
            ]);
    }
    
    public function update(Request $request)
    {
        $now=carbon::now();
        //fullUpdate
        if($request->color=="fullTitleUpdate!!"){
            
            //変更前
            $oldData=DB::table("s_events")
            ->where('id',$request->dataId)->first();
            
            $startYmd=carbon::parse($oldData->target_at)->copy()->startOfMonth();
            $endYmd=carbon::parse($oldData->target_at)->copy()->endOfMonth();
            
            
            //変更前の名前と同じ名前のものすべて更新。
            DB::table("s_events")
            ->where('title',$oldData->title)
            ->whereBetWeen("target_at",[$startYmd,$endYmd])
            ->update(['title'=>$request->name,'updated_at'=>$now]);
        
        //onlyUpdate    
        }else{
            DB::table("s_events")
            ->where('id',$request->dataId)
            ->update([
                "title"=>$request->name,
                "color"=>$request->color,
                "address"=>$request->address,
                "contents"=>$request->eventContents,
                'updated_at'=>$now
                ]);
        }
        
    }
    
    
    public function ankenIndex(Request $request,$selectPage=1,$code="none",$depa="all"){
        $ankens=DB::table('s_ankens')
            ->leftjoin('departments as depa','s_ankens.depa_id','=','depa.id')
            ->whereNull("s_ankens.deleted_at");
        
        
        //検索ボタンを押したとき
        if(isset($request->searchBtn)){
            $code=$request->code==""?"none":$request->code;
            $depa=$request->depa;
        }
        
        if($code!="none"){
            $ankens=$ankens->where('s_ankens.code','like','%'.$code.'%');
        }
        if($depa!='all'){
            $ankens=$ankens->where('s_ankens.depa_id',$depa);
        }
            
        $ankens=$ankens->select('s_ankens.*','depa.name as depa_name')->orderBy('s_ankens.id','desc');
        
        $page=($selectPage*8)-8;
        $countDocuments=$ankens->count();
        $pageCount=$countDocuments/8;
        $pageCount=(int)$pageCount + ($countDocuments%8!=0?1:0);
        
        //選択できる下に表示されるページ
        if(in_array($selectPage,[1,2,3,4,5])){
            for ($i = 1; $i <= min(9, $pageCount); $i++) {
                $pageNumber[] = $i;
            }
        }else{
            for ($i = $selectPage-4 ; $i <= min($selectPage+4, $pageCount); $i++) {
                $pageNumber[] = $i;
            }
        }
        //何もないとき
        if(!isset($pageNumber)){
            $pageNumber[]=1;
            $pageCount=1;
        }            
                    
        
        
        return view('schedule.ankenIndex',[
            'events'=>DB::table("s_events")->where("title","!=","振替休日")->select("title")->groupBy("title")->orderBy("target_at","desc")->get(),
            'code'=>$code,
            'depa'=>$depa,
            'pageNumber'=>$pageNumber,
            'selectPage'=>$selectPage,
            'pageCount'=>$pageCount,
            "ankens"=>$ankens->offset($page)->limit(8)->get(),
            "depas"=>DB::table('departments')->get()
            ]);
        
        
    }
    
    
    
    
    
    
    // public function ankenCreate(){
    //     return view('schedule.ankenCreate');
    // }
    
    public function ankenStore(Request $request){
        DB::table('s_ankens')->insert([
            "name"=>$request->name,
            "code"=>$request->code,
            "color"=>$request->color,
            "colorName"=>$request->colorName,
            // "tank"=>$request->tank,
            "depa_id"=>$request->depa,
            "address"=>$request->address
            ]);
        return back();
    }
    
    public function ankenGet(Request $request){
        
        // dd($request->ankenId,$request->ankenDate);
        
        $data=DB::table('s_events')
        ->join('gwusers','gwusers.id','=','s_events.user_id')
        ->select(
            'title_id',
            DB::raw("cast(sum(if(gwusers.tanka_kbn='B',30000,35000)) as SIGNED) as ankenTanka"),
            DB::raw("count(s_events.id) as ankenSu"),
            DB::raw("if(gwusers.tanka_kbn='B',30000,35000) as oneDayTanka"),
            'gwusers.name',
            's_events.id'
            );
        // ->where(DB::raw('year(target_at)'),carbon::parse($request->ankenDate)->year)
        // ->where(DB::raw('month(target_at)'),carbon::parse($request->ankenDate)->month);
        
        if($request->ankenId!="all"){
            $data=$data->where('title_id',$request->ankenId)
            ->groupBy('user_id','title_id')->get();
        }else{
            // dd($data->get());
            $data=$data->groupBy('title_id')->get();
        }
        
        return  response()->json($data);
    }
    
    
    
    
    
    public function ankenDelete(Request $request){
        s_anken::find($request->ankenId)->delete();
        return back();
    }
    
    public function ankenUpdate(Request $request){
        DB::table('s_ankens')->where('id',$request->ankenId)->update([
            "name"=>$request->name,
            "code"=>$request->code,
            "color"=>$request->color,
            "colorName"=>$request->colorName,
            // "tank"=>$request->tank,
            "depa_id"=>$request->depa,
            "address"=>$request->address
            ]);
        return back();
    }
    
    
    public function genkaIndex(Request $request,$id){

        $target=DB::table("s_ankens")->where("id",$id)->first();

        $romuNames=DB::table("s_events")
            ->join("gwusers","gwusers.id","=","s_events.user_id")
            ->where("title",$target->name)
            ->select("gwusers.name","s_events.target_at")
            ->get();
        
        $userName=[];
        foreach($romuNames as $romuName){
            if(isset($userName[$romuName->target_at])){
                $userName[$romuName->target_at].=str_replace('　','',str_replace(' ','',$romuName->name)).' ';    
            }else{
                $userName[$romuName->target_at]=str_replace('　','',str_replace(' ','',$romuName->name)).' ';    
            }
        }
        
        
        
                    
        
        $events=DB::table("s_events")
            ->join("gwusers","gwusers.id","=","s_events.user_id")
            ->where("title",$target->name)
            ->select(
                "gwusers.name",
                "s_events.target_at",
                DB::raw("sum(case gwusers.tanka_kbn
                when 'A' then 35000
                when 'B' then 30000
                end)
                 as gaku"),
                 
                DB::raw("sum(case gwusers.tanka_kbn
                when 'A' then 35000
                else 0
                end)
                 as Agaku"),
                 
                DB::raw("sum(case gwusers.tanka_kbn
                when 'B' then 30000
                else 0
                end)
                 as Bgaku"),
                 
                "gwusers.tanka_kbn"
                )
                ->groupBy("target_at");
            
        $romus=DB::table("s_genkas")->where("dt_kbn",4);
        
        $data=DB::table(DB::raw("({$events->toSql()}) as s_events"))
        ->leftjoin(DB::raw("({$romus->toSql()}) as romus"),"s_events.target_at","=","romus.target_at")
        ->setBindings(array_merge($events->getBindings(),$romus->getBindings()))
        ->select(
            "s_events.target_at",
            "Agaku","Bgaku","s_events.gaku","romus.biko"
            )
        ->get();
    
        
        $zais=DB::table("s_genkas")->where("anken_id",$id)->where("dt_kbn",1)->orderBy("target_at")->get();
        $gais=DB::table("s_genkas")->where("anken_id",$id)->where("dt_kbn",2)->orderBy("target_at")->get();
        $keis=DB::table("s_genkas")->where("anken_id",$id)->where("dt_kbn",3)->orderBy("target_at")->get();
        
        return view('schedule.genkaIndex',[
            'ankenId'=>$id,
            'zais'=>$zais,
            'gais'=>$gais,
            'keis'=>$keis,
            'sumZai'=>array_sum(array_column($zais->toArray(),"gaku")),
            'sumGai'=>array_sum(array_column($gais->toArray(),"gaku")),
            'sumKei'=>array_sum(array_column($keis->toArray(),"gaku")),
            'sumRomu'=>array_sum(array_column($data->toArray(),"gaku")),
            'romus'=>$data,
            'userName'=>$userName,
            "target"=>$target
            ]);
    }
    
    public function genkaStore(Request $request,$id){
         DB::table('s_genkas')->insert([
            "dt_kbn"=>$request->dt_kbn,
            "anken_id"=>$id,
            "target_at"=>$request->target_at,
            "torisaki_nm"=>$request->torisaki_nm,
            "gaku"=>$request->gaku,
            "biko"=>$request->biko
            ]);
            
        return back()->with(["dt_kbn"=>$request->dt_kbn]);
    }
    
    
    public function genkaUpdate(Request $request){
         
         DB::table('s_genkas')->where("id",$request->id)->update([
            "target_at"=>$request->target_at,
            "torisaki_nm"=>$request->torisaki_nm,
            "gaku"=>$request->gaku,
            "biko"=>$request->biko
            ]);
            
        return back()->with(["dt_kbn"=>$request->dt_kbn]);
    }
    
    public function genkaDelete(Request $request){
        DB::table('s_genkas')->where("id",$request->id)->delete();
        return back()->with(["dt_kbn"=>$request->dt_kbn]);
    }
    
    
    public function genkaPdf($id){
        
        $target=DB::table("s_ankens")->where("id",$id)->first();

        $romuNames=DB::table("s_events")
            ->join("gwusers","gwusers.id","=","s_events.user_id")
            ->where("title",$target->name)
            ->select("gwusers.name","s_events.target_at")->get();
        
        $userName=[];
        foreach($romuNames as $romuName){
            if(isset($userName[$romuName->target_at])){
                $userName[$romuName->target_at].=str_replace('　','',str_replace(' ','',$romuName->name)).' ';    
            }else{
                $userName[$romuName->target_at]=str_replace('　','',str_replace(' ','',$romuName->name)).' ';    
            }
        }
        
        $events=DB::table("s_events")
            ->join("gwusers","gwusers.id","=","s_events.user_id")
            ->where("title",$target->name)
            ->select(
                "gwusers.name",
                "s_events.target_at",
                DB::raw("sum(case gwusers.tanka_kbn
                when 'A' then 35000
                when 'B' then 30000
                end)
                 as gaku"),
                 
                DB::raw("sum(case gwusers.tanka_kbn
                when 'A' then 35000
                else 0
                end)
                 as Agaku"),
                 
                DB::raw("sum(case gwusers.tanka_kbn
                when 'B' then 30000
                else 0
                end)
                 as Bgaku"),
                 
                "gwusers.tanka_kbn"
                )
                ->groupBy("target_at");
            
        $romus=DB::table("s_genkas")->where("dt_kbn",4);
        
        $data=DB::table(DB::raw("({$events->toSql()}) as s_events"))
        ->leftjoin(DB::raw("({$romus->toSql()}) as romus"),"s_events.target_at","=","romus.target_at")
        ->setBindings(array_merge($events->getBindings(),$romus->getBindings()))
        ->select(
            "s_events.target_at",
            "Agaku","Bgaku","s_events.gaku","romus.biko"
            )
        ->get();
        $zais=DB::table("s_genkas")->where("anken_id",$id)->where("dt_kbn",1)->orderBy("target_at")->get();
        $gais=DB::table("s_genkas")->where("anken_id",$id)->where("dt_kbn",2)->orderBy("target_at")->get();
        $keis=DB::table("s_genkas")->where("anken_id",$id)->where("dt_kbn",3)->orderBy("target_at")->get();
        
        return view('schedule.genkaPdf',[
            'ankenId'=>$id,
            'zais'=>$zais,
            'gais'=>$gais,
            'keis'=>$keis,
            'sumZai'=>array_sum(array_column($zais->toArray(),"gaku")),
            'sumGai'=>array_sum(array_column($gais->toArray(),"gaku")),
            'sumKei'=>array_sum(array_column($keis->toArray(),"gaku")),
            'sumRomu'=>array_sum(array_column($data->toArray(),"gaku")),
            'romus'=>$data,
            'userName'=>$userName,
            "target"=>$target
            ]);
    }
    
    
   
    
    
    
}
