<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use carbon\Carbon;

class MitumoriCommonController extends Controller
{
    public function addKikaku(Request $request){
        
        // dd($request->all(),\str_replace("_anchor","",$request->code));
        $code=str_replace("_anchor","",$request->code).'0000';
        
        //似たコードの単位を渡す
        if(DB::table("sizai_kikakus")->where("mitumoriId",null)->where("code","like",$code."%")->exists()){
            $sizai=DB::table("sizai_kikakus")->where("mitumoriId",null)->where("code","like",$code."%")->first();
            $tani=$sizai->tani;
            $yosokake=$sizai->yosokake;
        }else{
            $tani="";
            $yosokake="";
        }

        DB::table("sizai_kikakus")->insert([
            "gyoNo"=>0,
            "code"=>$code,
            "tani"=>$tani,
            "yosokake"=>$yosokake,
            "name"=>"",
            "mitumoriId"=>$request->mitumoriId,
            ]);


        return \response()->json([
            "status"=>true,
            "message"=>"規格を追加しました",
        ]);
    }

    public function deleteKikaku(Request $request){
        DB::table("sizai_kikakus")->where("id",$request->kikakuId)->delete();
        DB::table("kikaku_name_for_mitumoris")->where("kikakuId",$request->kikakuId)->delete();

        return \response()->json([
            "status"=>true,
            "message"=>"規格を削除しました",
        ]);

    }



}
