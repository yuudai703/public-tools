<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Arr;

class DailyreportsKanriController extends Controller
{
    //カレンダー機能
   //===============================================================================
   public function calendar($id=1){
       
       $start=carbon::today()->subMonth()->format('Y-m-d');
       $end=carbon::today()->addMonth()->format('Y-m-d');
       
       $events=DB::table('kintai_holidays')->whereBetWeen('ymd',[$start,$end])
       ->select(
           DB::raw("case holiday_kbn when 1 then '指定休日' else '法定休日' end as title"),
           DB::raw("ymd as start"),
           DB::raw("'true' as allDay"),
           DB::raw("case holiday_kbn when 1 then 'yellow' else '#00FFFF' end as color")
           )->get();
        // dd($id);   
        if($id==2){
        
            return view('calendarKanri',[
                'events'=>json_encode($events)
            ]);
            
        //$id==1
        }else{
            return view('calendar',[
                'events'=>json_encode($events)
            ]);
        }
   }
   
   public function holiday(Request $request){
       DB::table('kintai_holidays')->where('ymd',$request->date)->delete();
       DB::table('kintai_holidays')
       ->insert([
           'ymd'=>$request->date,
           'holiday_kbn'=>$request->title
           ]);
           
        return response()->json('ok');
   }
   
   public function holidayDelete(Request $request){
      $date=carbon::parse( $request->date)->format('Y-m-d');
       DB::table('kintai_holidays')->where('ymd',$date)->delete();
           
        return response()->json('ok');
   }
   
   public function holidayGet(Request $request){
       $start=carbon::parse($request->date1)->format('Y-m-d');
       $end=carbon::parse($request->date2)->format('Y-m-d');
       $datas=DB::table('kintai_holidays')->whereBetWeen('ymd',[$start,$end])
       ->select(
           DB::raw("case holiday_kbn when 1 then '指定休日' else '法定休日' end as title"),
           DB::raw("ymd as start"),
           DB::raw("'true' as allDay"),
           DB::raw("case holiday_kbn when 1 then 'yellow' else '#00FFFF' end as color")
           )->get();
       
        return response()->json($datas);
   }
   //================================================================================================
   
}
