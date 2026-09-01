@extends('layouts.app')
@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
<link rel="stylesheet" href="{{ asset('css/mitumori.css') }}"/>
<script src="{{asset('/js/jstree.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

@include('mitumoriSetubiKani.hiroiM')
@include('mitumoriSetubiKani.keihiM')
@include('mitumoriSetubiKani.printM')
@include('mitumoriSetubiKani.informationM')
{{-- @include('mitumoriSetubiKani.addRow') --}}


<script>
    const mitumoriId="{{ $mitumori->id }}";
    console.log("{{ $mitumori->id }}", mitumoriId);
</script>

<style>

    .disabled-link {
        pointer-events: none;
        /* opacity: 0.5; */
        background-color:rgb(127, 176, 185)!important;
    }

    /* ＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝ */
/* https://snippet.skipjack.tokyo/snippets/radioButton/ */
    .radio_card{
        display:inline-block;
        margin:0px;
        margin-top:3px;
        cursor:pointer;
        width:50%;
    }
    .radio_card input{
        display:none
    }
    .radio_card span{
        display:block;
        /* width:60px; */
        padding:1px;
        border:2px solid #aaa;
        border-radius:8px;
        background:#fafafa;
        text-align:center;
        font-size:12px;
        transition:.3s
    }
        .radio_card input:checked+span{
        border-color:#2196f3;
        background:#e3f2fd
        }
    /*＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝ */

    

    .mitumori-header{
        /* background-color: rgb(98, 147, 159); */
        background-color: rgb(9, 103, 124);
        color: white;
        /* max-height: 10px; */
    }
    .btnStyle{
        background-color: rgb(9, 103, 124);
        cursor: pointer;
    }


    /* セレクトboxの矢印を変更 */
    select {
        -webkit-appearance: none!important;/* ベンダープレフィックス(Google Chrome、Safari用) */
        -moz-appearance: none!important; /* ベンダープレフィックス(Firefox用) */
        appearance: none!important; /* 標準のスタイルを無効にする */
    }
    ::-ms-expand { /* select要素のデザインを無効にする（IE用） */
        display: none!important;
    }

    ::placeholder {
        color: rgb(106, 106, 106)!important;
        font-weight: bold!important;
        }
    
    .input4::placeholder,
    .input7::placeholder,
    .input8::placeholder,
    .input9::placeholder,
    .rom_gaku::placeholder 
     {
        /* color: red!important; */
        font-size: 12px!important;
    }
    
    .gyoTextClass::placeholder {
        font-size: 12px!important;
        color: rgb(173, 173, 173)!important;
    }

</style>

<div>
    <h1 class="text-2xl float-left mt-1 w-[50vw] overflow-hidden whitespace-nowrap">
        {{ $mitumori->comName}}&nbsp;&nbsp;&nbsp;{{$mitumori->title }}
    </h1>
    {{-- <a href="{{route('mitumoriSetubi.index')}}" class="shadow-md shadow-cyan-950 btnStyle mt-1 mb-4 mr-3 float-right block text-white  focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-1 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
        戻る
    </a> --}}
    @include('mitumoriSetubiKani.kansetuhiM')
    {{-- <form action="{{route('mitumoriKani.addRow',['id'=>$mitumori->id])}}" method="POST" enctype="multipart/form-data"> --}}
        @csrf
        {{-- <button type="submit" class="shadow-md shadow-cyan-950 btnStyle mr-3 mt-1 float-right block text-white  focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-1 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            行追加
        </button> --}}
        {{-- <button type="submit" name="keihi_flg" value="2" class="keihiB shadow-md shadow-cyan-950 btnStyle mr-3 mt-1 float-right block text-white  focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-1 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            経費追加
        </button> --}}
        {{-- <button id="addRowBtn" type="button" class="mt-1 shadow-md shadow-cyan-950 btnStyle mr-3 float-right block text-white  focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-1 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" data-modal-target="addRow-modal" data-modal-toggle="addRow-modal">行追加</button> --}}
        <button id="keihiBtn" type="button" class="mt-1 shadow-md shadow-cyan-950 btnStyle mr-3 float-right block text-white  focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-1 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" data-modal-target="keihi-modal" data-modal-toggle="keihi-modal">その他 追加</button>
        <button id="infoBtn" type="button" class="mt-1 shadow-md shadow-cyan-950 btnStyle mr-3 float-right block text-white  focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-1 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" data-modal-target="info-modal" data-modal-toggle="info-modal">見積情報編集</button>
        <button id="printBtn" type="button" class="mt-1 shadow-md shadow-cyan-950 btnStyle mr-3 float-right block text-white  focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-1 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" data-modal-target="print-modal" data-modal-toggle="print-modal">印刷</button>
        <button style="right:595px;" id="sizaiBtn" type="button" class="absolute mt-1 shadow-md shadow-cyan-950 btnStyle mr-3 float-right block text-white  focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-1 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" data-modal-target="hiroi-modal" data-modal-toggle="hiroi-modal">拾う</button>
    {{-- </form> --}}

    <div style="right:680px; " class="absolute z-4 mt-1 mr-3 float-right shadow-sm flex w-fit shadow-cyan-950 rounded-md overflow-hidden">
        <a style="background-color:rgb(9, 103, 124);" href="{{route('mitumoriSetubiKani.past',['id'=>$mitumori->id])}}" id="past" class="@if($past==false) disabled-link @endif py-1 text-white inline-flex items-center px-3 text-sm  bg-gray-200  rounded-e-0 border-none rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
            ≪戻
        </a>
        <label class="text-center rounded-none rounded-e-0 max-w-24  bg-gray-300 border  focus:ring-blue-500 focus:border-blue-500 block flex-1 text-sm border-gray-300 p-2.5 py-1  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="">
            変更内容
        </label>
        <a style="background-color:rgb(9, 103, 124);" href="{{route('mitumoriSetubiKani.future',['id'=>$mitumori->id])}}" id="future" class="@if($future==false) disabled-link @endif py-1 text-white rounded-none rounded-e-md inline-flex items-center px-3 text-sm  bg-gray-200 border-none  dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
            進≫
        </a>
    </div>

   
    
</table>



</div>

<br>

<div class="grid grid-flow-col gap-8 m-0 mt-6">
    <div class="col-span-7">
        <div class="overflow-y-auto" style="max-height: 71vh!important;">
            <table class="w-full mainTable">
            <thead class="sticky top-0">
                <tr>
                    <td class="flex bg-gray-200 relative">
                        {{-- <label class="radio_card"><input onchange="dragOrGyoText('drag')" checked type="radio" name="card2"><span>drag</span></label>
                        <label class="radio_card"><input onchange="dragOrGyoText('gyoText')" type="radio" name="card2"><span>Key</span></label> --}}
                        <div class="flex absolute bg-gray-200 w-[64px] h-[32px] -top-5 -left-1">
                            &nbsp;
                        </div>
                    </td>
                    <td colspan="6" class=" text-xs bg-gray-200">
                        <span class="flex">
                            <label class="bg-white pr-2 inline-flex items-center ml-1  mb-[3px] cursor-pointer border-dashed border-2 border-gray-400 hover:bg-gray-100 hover:text-blue-700 focus:outline-none rounded-xl align-middle">
                            <input name="kansetu_auto_calculate" id="kansetu_auto_calculate" type="checkbox" class=" m-0 sr-only peer outline-none" {{$mitumori->kansetu_auto_calculate==1?"checked":""}} oninput="mitumoriEdit({{ $mitumori->id }},this.name,this.checked)">
                            <div class="text-center relative w-16 h-5  bg-gray-400 outline-hidden  dark:peer-focus:ring-blue-800 rounded-full peer leading-none dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white peer-checked:after:content-['on'] after:content-['off'] after:absolute after:top-[2px] after:start-[2px]  peer-checked:after:start-[-2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-8 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600 dark:peer-checked:bg-blue-600"></div>
                        <span class="hover:text-blue-700 ms-3 text-sm  m-0 font-medium text-gray-900 dark:text-gray-300">間接費自動計算</span>
                        </label>
                            <div class="flex h-6 w-[360px] ml-3">
                                {{-- 税率% --}}
                                <input name="tax_rate" type="hidden" oninput="mitumoriEdit({{ $mitumori->id }},this.name,this.value)" max="100" value="{{$mitumori->tax_rate}}" id="tax_rate">
                                <span class="inline-flex items-center px-1 text-sm text-gray-900 bg-slate-200 border rounded-e-0 border-gray-400 border-e-0 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                    税抜額
                                </span>
                                <span id="mitumoriGakuHeader" class="text-right rounded-none  bg-gray-300 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-400 py-[1px] pr-2  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    {{ $mitumoriSais->sum('gaku')+$mitumori->rom_gaku}}
                                        {{-- +round($mitumori->rom_tankaDe*$mitumoriSais->sum('bugakariDeSum'))
                                        +round($mitumori->rom_tanka*$mitumoriSais->sum('bugakariSum'))
                                        +round($mitumori->rom_tankaTo*$mitumoriSais->sum('bugakariToSum')) 
                                        }} --}}
                                </span>
                                <span class="inline-flex items-center px-1 text-sm text-gray-900 bg-slate-200 border border-gray-400  dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                    円 
                                </span>
                                <span class="inline-flex items-center px-1 text-sm text-gray-900 bg-slate-200 border border-gray-400  dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                    税込額
                                </span>
                                <span  id="taxKomiGaku" class="text-right rounded-none  bg-gray-300 border text-gray-900 border-gray-400 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm  py-[1px] pr-2  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" >
                                </span>
                                <span  class="inline-flex items-center px-1 text-sm text-gray-900 bg-slate-200 border border-gray-400 rounded-e-lg dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                    円  
                                </span>
                            </div>
                            </span> 
                    </td>
                    <td class="flex  bg-gray-200">
                        <label class="radio_card"><input checked type="radio" name="card" onchange="bikoOrMemo('biko')" ><span>備考</span></label>
                        <label class="radio_card"><input type="radio" name="card" onchange="bikoOrMemo('memo')"><span>メモ</span></label>
                    </td>
                    <td colspan="9" class="bg-gray-200 items-end">
                        <form id="addRow-store" action="{{route('mitumoriSetubiKani.addRow',['id'=>$mitumori->id])}}" method="post">
                            @csrf
                            <button name="zai_kbn" value="" style="top:6px; right:0px;" class="text-sm mr-1 absolute  hover:bg-blue-200 hover:border-blue-900 hover:text-blue-700 font-medium text-gray-800 rounded-md min-w-20 border border-gray-400 bg-white ">どちらでもない</button>
                            <button name="zai_kbn" value="EB000" style="top:6px; right:103px;" class="text-sm mr-1 absolute hover:bg-blue-200 hover:border-blue-900 hover:text-blue-700 font-medium text-gray-800 rounded-md min-w-20 border border-gray-400 bg-white ">B材追加</button>
                            <button name="zai_kbn" value="EA000" style="top:6px; right:187px;" class="text-sm mr-1 absolute hover:bg-blue-200 hover:border-blue-900 hover:text-blue-700 font-medium text-gray-800 rounded-md min-w-20 border border-gray-400 bg-white ">A材追加</button>
                        </form>
                        {{-- <span style="top:8px; right:270px;" class="float-right text-sm -mb-2 absolute">行追加：</span> --}}
                    </td>
                </tr>
                <tr>
                    <th class="mitumori-header text-xs border border-gray-400 w-[60px]   text-center drag">action</th>
                    <th class="mitumori-header text-xs border border-gray-400 w-[4%]  text-center gyoText hidden">キー</th>
                    <th class="mitumori-header text-xs border border-gray-400 w-[20%]">品名</th>
                    <th class="mitumori-header text-xs border border-gray-400 w-[20%]">仕様</th>
                    <th class="mitumori-header text-xs border border-gray-400 w-[4%] ">数量</th>
                    <th class="mitumori-header text-xs border border-gray-400 w-[4%] ">単位</th>
                    <th class="mitumori-header text-xs border border-gray-400 w-[8%]">単価</th>
                    <th class="mitumori-header text-xs border border-gray-400 w-[8%]">金額</th>
                    <th class="mitumori-header text-xs border border-gray-400 w-[10%] biko">備考</th>
                    <th class="bg-gray-500 text-white text-xs border border-gray-400 w-[10%] memo hidden">メモ</th>
                    <th class="mitumori-header text-xs border border-gray-400 w-[4.5%] btanka" nowrap>歩単</th>
                    <th class="mitumori-header hidden text-xs border border-gray-400 w-[4.5%] btanka" nowrap>普通歩単</th>
                    <th class="mitumori-header hidden text-xs border border-gray-400 w-[4.5%] btanka" nowrap>特殊歩単</th>
                    <th class="mitumori-header text-xs border border-gray-400 w-[4.5%] bsum" nowrap>歩掛</th>
                    <th class="mitumori-header hidden text-xs border border-gray-400 w-[4.5%] bsum" nowrap>普通歩掛</th>
                    <th class="mitumori-header hidden text-xs border border-gray-400 w-[4.5%] bsum" nowrap>特殊歩掛</th>
                    <th class="mitumori-header text-xs border border-gray-400 w-[4%]" nowrap>撤去率</th>
                    <th class="mitumori-header text-xs border border-gray-400 w-[4%] status" nowrap>登録値</th>
                    <th class="mitumori-header text-xs border border-gray-400"></th>
                </tr>
            </thead>
            <tbody class="tbody">
                <?PHP $hiyo_kbn=0; ?>
                @if(isset($mitumoriSais))
                    @foreach($mitumoriSais as $ms)
                    {{-- 資材以外はドラッグ操作できないようにする --}}
                    @if($ms->hiyo_kbn <> $hiyo_kbn )</tbody> <tbody class="tbody"> @endif
                    <?PHP $hiyo_kbn=$ms->hiyo_kbn; ?>
                    {{-- ーーーーーーーーーーーーーーーーーーーー --}}
                    
                    <tr data-id="{{ $ms->id }}">
                        <td class="bg-white text-xs border border-gray-400 drag p-0">
                            @if($hiyo_kbn==0 || $hiyo_kbn==2 || $hiyo_kbn==4  || $hiyo_kbn==5)
                                <span class="flex flex-nowrap">
                                        <svg class="handle  cursor-grab w-[50%] h-4 text-center border border-gray-400 {{ $hiyo_kbn==0?'bg-green-100 hover:bg-green-200':'bg-orange-100 hover:bg-orange-200'}} text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                        </svg>
                                        <svg onclick="test(event,this)" title="複製" class="border-gray-400  cursor-pointer w-[50%] h-4 text-center border bg-purple-300 hover:bg-purple-400 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" title="複製" stroke-linejoin="round" stroke-width="2" d="M9 8v3a1 1 0 0 1-1 1H5m11 4h2a1 1 0 0 0 1-1V5a1 1 0 0 0-1-1h-7a1 1 0 0 0-1 1v1m4 3v10a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1v-7.13a1 1 0 0 1 .24-.65L7.7 8.35A1 1 0 0 1 8.46 8H13a1 1 0 0 1 1 1Z"/>
                                        </svg>
                                    </span>
                            @else
                                <input style="overflow-y: hidden;" class="h-full text-center border-none w-full text-xs p-0   bg-gray-200" type="text" value="{{ (in_array($ms->hiyo_kbn,[0])?$ms->gyoNo:(in_array($ms->hiyo_kbn,[3])?'間接費':'')).' '.($ms->zai_kbn=='bzai'?'B材':'').($ms->zai_kbn=='azai'?'A材':'')}}"  disabled >
                            @endif
                        </td><!--行ナンバー-->

                        <td class="bg-white text-xs border border-gray-400 gyoText hidden">
                            @if($hiyo_kbn==0)
                                <input type="text" placeholder="{{ $ms->gyoNo }}" value="{{ $ms->gyoText }}" oninput="mitumoriSai(this.value,'{{$ms->id}}','gyoText')" class="gyoTextClass h-full text-center border-none w-full text-xs p-0 bg-wite input10">
                            @else
                                <input style="overflow-y: hidden;" class="h-full text-center border-none w-full text-xs p-0   bg-gray-200" type="text" value="{{ (in_array($ms->hiyo_kbn,[0])?$ms->gyoNo:(in_array($ms->hiyo_kbn,[3])?'間接費':'')).' '.($ms->zai_kbn=='bzai'?'B材':'').($ms->zai_kbn=='azai'?'A材':'')}}"  disabled >
                            @endif
                        </td><!--行ナンバー-->

                        <td class="bg-white text-xs border border-gray-400"><input name="name" style="overflow-y: hidden;" oninput="mitumoriSai(this.value,'{{$ms->id}}','name')" class="h-full border-none w-full text-xs p-0 pr-1 pl-1 {{in_array($ms->hiyo_kbn,[0,2])?'input0':''}} @if(in_array($ms->hiyo_kbn,[1,3])) bg-gray-200 @endif" type="text" value="{{ $ms->name }}"  @if(in_array($ms->hiyo_kbn,[1,3])) disabled @endif></td><!--品名-->
                        <td class="bg-white text-xs border border-gray-400"><input name="siyo" style="overflow-y: hidden;" oninput="mitumoriSai(this.value,'{{$ms->id}}','siyo')" class="h-full border-none w-full text-xs p-0 pr-1 pl-1 input1" type="text" value="{{ $ms->siyo }}"></td><!--仕様-->
                        <td class="bg-white text-xs border border-gray-400"><input name="su" style="overflow-y: hidden;" class="karaEv h-full border-none suCell w-full text-xs p-0 text-right placeholder:text-xs placeholder:text-gray-400 {{in_array($ms->hiyo_kbn,[0,2])?'input2':''}} @if(in_array($ms->hiyo_kbn,[1,3])) bg-gray-200 @endif" type="number" value="{{ $ms->su }}" data-sais-id="{{ $ms->id }}" @if(in_array($ms->hiyo_kbn,[1,3])) disabled @endif placeholder="0"></td><!--数量-->
                        <td class="bg-white text-xs border border-gray-400"><input name="tani" style="overflow-y: hidden;" oninput="mitumoriSai(this.value,'{{$ms->id}}','tani')" class="h-full border-none w-full text-xs p-0 pr-1 pl-1 {{in_array($ms->hiyo_kbn,[0,2])?'input3':''}} @if(in_array($ms->hiyo_kbn,[1,3])) bg-gray-200 @endif" type="text" value="{{ $ms->tani }}" @if(in_array($ms->hiyo_kbn,[1,3])) disabled @endif list="tanis"></td><!--単位-->
                        <td class="bg-white text-xs border border-gray-400"><input name="tanka" style="overflow-y: hidden;" oninput="mitumoriSai(this.value,'{{$ms->id}}','tanka',event)" class="{{ $ms->tClassName }} karaEv h-full border-none w-full text-xs p-0 pr-1 pl-1 text-right input4" type="text"  value="{{ $ms->tanka==0&&in_array($ms->hiyo_kbn,[0,2])?'':($ms->hiyo_kbn==5?abs($ms->tanka):$ms->tanka) }}" placeholder="0" data-sizai-id="{{ $ms->tanka_rendo_code }}" data-kansetu-code="{{ $ms->kansetu_code }}" maxlength="9"></td><!--単価-->
                        <td class="bg-white text-xs border border-gray-400"><input name="gaku" style="overflow-y: hidden;" oninput="mitumoriSai(this.value,'{{$ms->id}}','gaku')" @disabled(true) class="{{ $ms->gClassName.' '.$ms->zai_kbn }} h-full bg-gray-200 border-none w-full text-xs p-0 pr-1 pl-1 text-right" type="text" value="{{ $ms->hiyo_kbn==5?abs($ms->gaku):$ms->gaku }}"></td><!--金額-->                       
                        <td class="bg-white text-xs border border-gray-400 biko"><input name="biko" style="overflow-y: hidden;" oninput="mitumoriSai(this.value,'{{$ms->id}}','biko')" class="h-full border-none w-full text-xs p-0 pr-1 pl-1  input5" type="text" value="{{ $ms->biko }}"></td><!--備考-->
                        <td class="bg-white text-xs border border-gray-400 memo hidden"><input name="memo" style="overflow-y: hidden;" oninput="mitumoriSai(this.value,'{{$ms->id}}','memo')" class="h-full border-none w-full text-xs p-0 pr-1 pl-1  input6" type="text" value="{{ $ms->memo }}"></td><!--備考-->
                        <td class="bg-white text-xs border border-gray-400"><input name="bugakariDe" style="overflow-y: hidden;" oninput="mitumoriSai(this.value,'{{$ms->id}}','bugakariDe',event)" class="karaEv h-full border-none w-full text-xs p-0 pr-1 pl-1 text-right bugakariDeTankaCell input7" type="text" placeholder="0" value="{{ $ms->bugakariDe==0?'':$ms->bugakariDe }}" data-bugakaride-code="{{ $ms->bugakari_rendo_code }}"></td><!--歩掛-->
                        <td class="bg-white text-xs border border-gray-400 hidden"><input name="bugakari" style="overflow-y: hidden;" oninput="mitumoriSai(this.value,'{{$ms->id}}','bugakari')" class="h-full border-none hidden w-full text-xs p-0 pr-1 pl-1 text-right bugakariTankaCell input8" type="text" placeholder="0" value="{{ $ms->bugakari==0?'':$ms->bugakari }}" data-bugakari-code="{{ $ms->bugakari_rendo_code }}"></td><!--歩掛-->
                        <td class="bg-white text-xs border border-gray-400 hidden"><input name="bugakariTo" style="overflow-y: hidden;" oninput="mitumoriSai(this.value,'{{$ms->id}}','bugakariTo')" class="h-full border-none hidden w-full text-xs p-0 pr-1 pl-1 text-right bugakariToTankaCell input9" type="text" placeholder="0" value="{{ $ms->bugakariTo==0?'':$ms->bugakariTo }}" data-bugakarito-code="{{ $ms->bugakari_rendo_code }}"></td><!--歩掛-->
                        <td class="bg-white text-xs border border-gray-400"><input style="overflow-y: hidden;" class="h-full border-none w-full text-xs p-0 pr-1 pl-1 text-right bg-gray-200 bugakariDeCell" type="text" value="{{ ((int)$ms->su)*$ms->bugakariDe*$ms->tekkyo_rate }}" @disabled(true) data-tekkyo-rate="{{ $ms->tekkyo_rate }}"></td><!--歩掛-->
                        <td class="bg-white text-xs border border-gray-400 hidden"><input style="overflow-y: hidden;" class="h-full border-none w-full text-xs p-0 pr-1 pl-1 text-right bg-gray-200 bugakariCell hidden" type="text" value="{{  ((int)$ms->su)*$ms->bugakari*$ms->tekkyo_rate }}" @disabled(true)></td><!--歩掛-->
                        <td class="bg-white text-xs border border-gray-400 hidden"><input style="overflow-y: hidden;" class="h-full border-none w-full text-xs p-0 pr-1 pl-1 text-right bg-gray-200 bugakariToCell hidden" type="text" value="{{  ((int)$ms->su)*$ms->bugakariTo*$ms->tekkyo_rate }}" @disabled(true)></td><!--歩掛-->
                        <td class="bg-gray-200 text-xs border border-gray-400 text-right pr-1">{{ $ms->tekkyo_rate==1?'':$ms->tekkyo_rate }}</td><!--撤去率-->
                        <td class="bg-gray-200 text-xs border border-gray-400 text-center pr-1" nowrap>{{ $ms->sai_status }}</td><!--登録値-->
                        <td class="p-0 bg-white text-xs border border-gray-400">
                            <form action="{{route('mitumoriSetubiKani.delete', $ms->id)}}" method="POST" class="w-full h-full">
                                @csrf
                                <button type="submit" class="deleteBtn w-full h-full bg-red-500 hover:bg-red-700 text-white" nowrap>削除</button>
                            </form>
                        </td>
                    </tr>
                    {{-- 資材以外はドラッグ操作できないようにする --}}
                    {{-- @if($loop->last ) </tfoot> @endif --}}
                    {{-- ーーーーーーーーーーーーーーーーーーーー --}}
                    
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <table class="w-full table-cells">
            <tr>
                <td class="border-b border-gray-400 w-[60px]  text-center">合計</td>
                
                <td class="border-b border-gray-400 text-right text-xs pr-1 sumGaku" style="width:64%; overflow: visible;">{{ $mitumoriSais->sum('gaku') }}</td>
                
                <td class="border-b border-gray-400 text-right text-xs pr-1 bugakariDeSum" style="width:19%;">{{ $mitumoriSais->sum('bugakariDeSum') }}</td>
                <td class="border-b border-gray-400 text-right text-xs pr-2 bugakariSum hidden">{{ $mitumoriSais->sum('bugakariSum') }}</td>
                <td class="border-b border-gray-400 text-right text-xs pr-2 bugakariToSum hidden">{{ $mitumoriSais->sum('bugakariToSum') }}</td>
                <td class="border-b  border-gray-400"></td>
            </tr>
    </table>
        <div class="flex justify-end mt-0 text-right etcFocus">
                 <div class="flex h-6 w-[430px] mt-1 mr-2">
                    <span class="inline-flex items-center px-1 text-sm text-gray-900 bg-amber-100 border rounded-e-0 border-gray-400 border-e-0 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                        労務単価
                    </span>
                    <input id="romTankaDe" value="{{ $mitumori->rom_tankaDe }}" name="rom_tankaDe" onchange="romChange()" oninput="mitumoriEdit({{ $mitumori->id }},this.name,this.value)" type="text" class="text-right rounded-none  bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-400 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="">
                    <span class="inline-flex items-center px-1 text-sm text-gray-900 bg-amber-100 border border-gray-400  dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                        円 
                    </span>
                    <span class="inline-flex items-center px-1 text-sm text-gray-900 bg-amber-100 border border-gray-400  dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                        労務費
                    </span>
                    <input @disabled(true) name="rom_gakuDe" value="{{round($mitumori->rom_tankaDe*$mitumoriSais->sum('bugakariDeSum'))}}" type="text" class="bugakariGaku text-right rounded-none  bg-amber-100 border text-gray-900 border-gray-400 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm  p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="">
                    <span class="inline-flex items-center px-1 text-sm text-gray-900 bg-amber-100 border border-gray-400 rounded-e-lg dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                        円  
                    </span>
                </div>

                <div class=" h-6 w-[430px] mt-1 mr-2 hidden">
                    <span class="inline-flex items-center px-1 text-sm text-gray-900 bg-green-100 border rounded-e-0 border-gray-400 border-e-0 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                        普通労務単価
                    </span>
                    <input id="romTanka" value="{{ $mitumori->rom_tanka }}" name="rom_tanka" onchange="romChange()" oninput="mitumoriEdit({{ $mitumori->id }},this.name,this.value)" type="text" class="text-right rounded-none  bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-400 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="">
                    <span class="inline-flex items-center px-1 text-sm text-gray-900 bg-green-100 border border-gray-400  dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                        円 
                    </span>
                    <span class="inline-flex items-center px-1 text-sm text-gray-900 bg-green-100 border border-gray-400  dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                        労務費
                    </span>
                    <input @disabled(true) name="rom_gaku" value="{{round($mitumori->rom_tanka*$mitumoriSais->sum('bugakariSum'))}}" type="text" class="bugakariGaku text-right rounded-none  bg-green-100 border text-gray-900 border-gray-400 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm  p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="">
                    <span class="inline-flex items-center px-1 text-sm text-gray-900 bg-green-100 border border-gray-400 rounded-e-lg dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                        円  
                    </span>
                </div>
            

                <div class=" h-6 w-[430px] mt-1 hidden">
                    <span class="inline-flex items-center px-1 text-sm text-gray-900 bg-indigo-200 border rounded-e-0 border-gray-400 border-e-0 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                        特殊労務単価
                    </span>
                    <input id="romTankaTo" value="{{ $mitumori->rom_tankaTo }}" name="rom_tankaTo" onchange="romChange()" oninput="mitumoriEdit({{ $mitumori->id }},this.name,this.value)" type="text"  class="text-right rounded-none  bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-400 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="">
                    <span class="inline-flex items-center px-1 text-sm text-gray-900 bg-indigo-200 border border-gray-400  dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                        円 
                    </span>
                    <span class="inline-flex items-center px-1 text-sm text-gray-900 bg-indigo-200 border border-gray-400  dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                        労務費
                    </span>
                    <input @disabled(true) name="rom_gakuTo" value="{{round($mitumori->rom_tankaTo*$mitumoriSais->sum('bugakariToSum'))}}" type="text" class="bugakariGaku text-right rounded-none  bg-indigo-200 border text-gray-900 border-gray-400 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm  p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="">
                    <span class="inline-flex items-center px-1 text-sm text-gray-900 bg-indigo-200 border border-gray-400 rounded-e-lg dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                        円  
                    </span>
                </div> 
                <div class="flex h-6 w-[430px] mt-1 float-right">
                    <span class="shadow-sm shadow-cyan-950 w-24 rounded-s-md rounded-e-md inline-flex items-center px-0 text-sm text-gray-900 border border-gray-400  dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                        <button class="w-full rekiBtn hover:text-blue-700 hover:bg-white rounded-s-md rounded-e-md" onclick="addAutoRom()">
                            自動計算反映
                        </button>
                    </span>
                    &nbsp;
                    <span class="rounded-s-md w-[122px] text-center items-center px-1 text-sm text-gray-900 border border-gray-400  dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                        提出労務費
                    </span>
                    <input name="rom_gaku" value="{{$mitumori->rom_gaku!=0?round($mitumori->rom_gaku):''}}" onchange="romGakuChange()" oninput="mitumoriEdit({{ $mitumori->id }},this.name,this.value)" placeholder="0" type="text" class="rom_gaku text-right rounded-none  border text-gray-900 border-gray-400 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm  p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="">
                    <span class="inline-flex items-center px-1 text-sm text-gray-900 border border-gray-400 rounded-e-lg dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                        円  
                    </span>
                </div>
        </div>
</div>
</div>


<datalist id="tanis">
    @foreach ($tanis as $tani)
        <option value="{{ $tani }}">{{$tani }}</option>
    @endforeach
</datalist>

<button id="sizaiBtn" data-modal-target="hiroi-modal" data-modal-toggle="hiroi-modal"></button>

{{-- {{ $kozis }} --}}

<script src="{{ asset('js/mitumori.js') }}"></script>
<script>

function test(e, ele) {
    console.log($(ele).parent().parent().parent().data("id"));
    const saiId = $(ele).parent().parent().parent().data("id");
    const $sss = $(ele).parent().parent().parent().clone(true);
    // 必要なら oninput を解除
    $sss.children().eq(1).children().eq(0).prop("oninput", null);
    $.ajax({
        type: 'post',
        url: 'copy/' + mitumoriId,
        data: { saiId: saiId },
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        timeout: 10000
    })
    .done(function(response) {
        $sss.find("input").each(function () {
            const $input = $(this);
            const name = $input.attr("name");
            
            if (["tanka", "bugakariDe", "bugakari", "bugakariTo"].includes(name)) {
                this.oninput = function (e2) {
                    mitumoriSai(e2.target.value, response.id, e2.target.name, e2);
                };
            } else if (["name", "siyo", "memo", "biko", "tani"].includes(name)) {
                this.oninput = function (e2) {
                    mitumoriSai(e2.target.value, response.id, e2.target.name);
                };
            }
        });

        

        $sss.attr("data-id",response.id).data("id", response.id);
        let ac="<?PHP echo route('mitumoriSetubiKani.delete','sid');?>";
        ac=ac.replace('sid',response.id);
        $sss.children().eq(17).children().eq(0).attr("action",ac);
        $(ele).parent().parent().parent().after($sss);
        rekicheck();
        $(ele).parent().parent().parent().next().find("td,input").each(function () {
            $(this).addClass("bg-yellow-100");
            $(this).css("transition", "background-color 1s ease");
        });
        
        $(ele).parent().parent().parent().next().find("td,input").each(function () {
            const $this = $(this);
            setTimeout(function () {
                $this.removeClass("bg-yellow-100");
                setTimeout(function () {
                    $this.css("transition", "");
                }, 1000);
            }, 1000);
        });

        (async function(){
            await kansetuSet();
            kansetu_auto_calculate(); // 消耗品雑材自動計算 *0.03
            sumKigaku();            // 合計金額を下に表示
            bugakariCalculate();    // 歩掛再計算
            bugakariEvent();        // 歩掛合計と労務費を出す
            kingakuAndRom();        // 合計金額と労務費を足して見出しの金額に入れる
            taxGaku();              // 税込額算出
        })();

    })
    .fail(function(xhr, textStatus, errorThrown) {
    });
}

// 削除ボタン マウスオーバー
$(document).on('mouseover', '.deleteBtn', function(e) {
    const trTag = $(e.target).closest('tr');
    trTag.find('td,input').css(
        'background-color',
        'rgb(255, 224, 205)'
    );
});

// 削除ボタン マウスアウト
$(document).on('mouseout', '.deleteBtn', function(e) {
    const trTag = $(e.target).closest('tr');
    trTag.find('td,input').css(
        'background-color',
        ''
    );
});


tippy('.btanka', {
   content: '資材1つあたりの歩掛',
   followCursor: true,
   animation: 'fade',
});

tippy('.bsum', {
   content: '数量×歩掛×撤去率',
   followCursor: true,
});
tippy('.status', {
    content: '登録時のステータス',
    followCursor: true,
});
tippy('.keihiB', {
    content: '消耗品雑材の計算対象外になります。',
});

const bugakari=document.querySelector('.bugakariSum').innerHTML;
const bugakariGaku=document.querySelector('.bugakariGaku');

const mitumoriKoId=null;//見積項目IDはなく使わないため、ajax　errorを回避するためにnullを入れる

// 例: oninput から呼ばれる想定
let mitumoriTimer = null;
let mitumoriXhr = null;
let composing = false; // IME対策（必要なら使う）
function mitumoriEdit(mitumoriId,name,value) {
  // IME変換中は送らない（必要なら
  // ）
  return new Promise(function(resolve) {
    if (composing){
        resolve();
        return;
    } 

  // デバウンス（400msは目安）
  clearTimeout(mitumoriTimer);
  mitumoriTimer = setTimeout(() => {
    // 直前の通信は中断
    if (mitumoriXhr && mitumoriXhr.abort) {
      mitumoriXhr.abort();
    }

    mitumoriXhr = $.ajax({
      type: 'PUT',
      url: 'onChange',
      data: {
        id: mitumoriId,
        name: name,
        value: value
      },
      // フォームURLエンコードで十分。JSONにしたいなら↓を使う（後述）
      // contentType はデフォルトのまま
      dataType: 'json', // サーバは必ず JSON を返す
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      timeout: 10000
    })
    .done(function(response) {
        console.log(1);
      // 必要ならUI更新
      // console.log('saved', response);
      //間接費自動計算にしたら自動リロードを入れる
      if(response.column=="kansetu_auto_calculate") location.reload();
    //   if(response.column=="rom_gaku") kingakuAndRom(); 

      
      resolve();
      
    })
    .fail(function(xhr, textStatus, errorThrown){
      // デバッグしやすいログ
     
      // ここで 419/422/500/parseerror 等の傾向がわかる
    });
  }, 400);
  })
}

// 例: oninput から呼ばれる想定
let mitumoriSaiTimer = {};
let mitumoriSaiXhr = {};
let composingSai = false; // IME対策（必要なら使う）
function mitumoriSai(v, id, name,eve=undefined) {

    if(( name=="tanka" || name=="bugakariDe" || name=="bugakari" || name=="bugakariTo") && karaKbn == false && eve.data!=undefined){
        v=eve.data;
        eve.target.value=eve.data;
        karaKbn=true;
    }


  // IME変換中は送らない（必要なら）
  return new Promise(function(resolve) {
    if (composingSai){
        resolve();
        return;
    } 

    // デバウンス（400msは目安）
    clearTimeout(mitumoriSaiTimer[id+name]);
    mitumoriSaiTimer[id+name] = setTimeout(() => {
            // 直前の通信は中断
            if (mitumoriSaiXhr[id+name]?.abort) {
                mitumoriSaiXhr[id+name].abort();
            }

            mitumoriSaiXhr[id+name] = $.ajax({
            type: 'PUT',
            url: 'onChange/sai',
            // クエリではなく data で渡す（自動エンコード）
            data: {
                id: id,
                name: name,
                value: v
            },
            // フォームURLエンコードで十分。JSONにしたいなら↓を使う（後述）
            // contentType はデフォルトのまま
            dataType: 'json', // サーバは必ず JSON を返す
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            timeout: 10000
            })
            .done(function(response) {
                resolve();
            });
        }, 400);
  })
}

const sizaiBtn = document.querySelector('#sizaiBtn');
const sizaiH = document.querySelector('#sizaiH');
const modalTable = document.querySelector('#modalTable');

let openCheck = false;

//労務単価変更時の処理
async function romChange(){
    // await kansetuSet();//間接費
    // sumKigaku();//合計金額を下に表示
    bugakariEvent();//buggakari合計と労務費を出す
    // kingakuAndRom();//合計金額と労務費を足して見出しの金額に入れる
    // taxGaku();//税込額算出
}

//電工歩掛再計算
// document.querySelectorAll('.bugakariDeTankaCell').forEach(element => {
//     element.addEventListener('change',async function(e){
//電工歩掛再計算
$(document).on('change', '.bugakariDeTankaCell', async function(e) {
        const bugakariRendoCode=e.target.dataset.bugakarideCode;
        document.querySelectorAll('[data-bugakaride-code="'+bugakariRendoCode+'"]').forEach(function(e2){
            if(bugakariRendoCode!='') e2.value=e.target.value;
        })
        await kansetuSet();//間接費
        bugakariCalculate();//su*bugakari
        sumKigaku();//合計金額を下に表示
        bugakariEvent();//buggakari合計と労務費を出す
        kingakuAndRom();//合計金額と労務費を足して見出しの金額に入れる
        taxGaku();//税込額算出
});
// });

//普通歩掛再計算
// document.querySelectorAll('.bugakariTankaCell').forEach(element => {
//     element.addEventListener('change',async function(e){
$(document).on('change', '.bugakariTankaCell', async function(e) {       
        const bugakariRendoCode=e.target.dataset.bugakariCode;
        document.querySelectorAll('[data-bugakari-code="'+bugakariRendoCode+'"]').forEach(function(e2){
            if(bugakariRendoCode!='') e2.value=e.target.value;
        })
        await kansetuSet();//間接費
        bugakariCalculate();//su*bugakari
        sumKigaku();//合計金額を下に表示
        bugakariEvent();//buggakari合計と労務費を出す
        kingakuAndRom();//合計金額と労務費を足して見出しの金額に入れる
        taxGaku();//税込額算出
});
// });


//特殊歩掛再計算
// document.querySelectorAll('.bugakariToTankaCell').forEach(element => {
//     element.addEventListener('change',async function(e){
$(document).on('change', '.bugakariToTankaCell', async function(e) {        
        const bugakariRendoCode=e.target.dataset.bugakaritoCode;
        document.querySelectorAll('[data-bugakarito-code="'+bugakariRendoCode+'"]').forEach(function(e2){
            if(bugakariRendoCode!='') e2.value=e.target.value;
        })
        await kansetuSet();//間接費
        bugakariCalculate();//su*bugakari
        sumKigaku();//合計金額を下に表示
        bugakariEvent();//buggakari合計と労務費を出す
        kingakuAndRom();//合計金額と労務費を足して見出しの金額に入れる
        taxGaku();//税込額算出
});
// });


function bugakariEvent(){
    (function(){
        let sumBgakari = new BigNumber(0);
        document.querySelectorAll('.bugakariDeCell').forEach(element => {
            sumBgakari=sumBgakari.plus(new BigNumber(element.value));
        });
        document.querySelector(".bugakariDeSum").innerHTML=sumBgakari.toString();
        //歩掛の更新は計算ボタンで処理されるようにしたため下記はコメントアウト7/28
        //コメントアウト解除10/18
        const tankaDe=new BigNumber(document.querySelector("#romTankaDe").value);
        document.querySelectorAll(".bugakariGaku")[0].value= Math.round( tankaDe.multipliedBy(sumBgakari).toString() );
    })();

    (function(){
        let sumBgakari = new BigNumber(0);
        document.querySelectorAll('.bugakariCell').forEach(element => {
            sumBgakari=sumBgakari.plus(BigNumber(element.value));
        });
        document.querySelector(".bugakariSum").innerHTML=sumBgakari.toString();

        const tanka=new BigNumber(document.querySelector("#romTanka").value);
        document.querySelectorAll(".bugakariGaku")[1].value=Math.round( tanka.multipliedBy(sumBgakari).toString() );
    })();

    (function(){
        let sumBgakari = new BigNumber(0);
        document.querySelectorAll('.bugakariToCell').forEach(element => {
            sumBgakari=sumBgakari.plus(new BigNumber(element.value));
        });
        document.querySelector(".bugakariToSum").innerHTML=sumBgakari.toString();
        //コメントアウト解除10/18
        const tankaTo= new BigNumber(document.querySelector("#romTankaTo").value);
        document.querySelectorAll(".bugakariGaku")[2].value=Math.round( tankaTo.multipliedBy(sumBgakari).toString() );
    })();
}


// 数量入力イベント
$(document).on('input', '.suCell', function (e) {
    // 数量にはイベントハンドラーがないためここで入力内容を制御する
    if (karaKbn == false && e.originalEvent.data != undefined) {
        this.value = e.originalEvent.data;
        karaKbn = true;
    }
});

// 数量変更イベント
$(document).on('change', '.suCell', async function (e) {

    // 数量セル
    const su = this;
    const saisId = this.parentNode.parentNode.dataset.id; // suCell

    await mitumoriSai(su.value, saisId, 'su');
    suKakeTanka();          // すべてのセルの 数量 × 単価
    await kansetuSet();
    kansetu_auto_calculate(); // 消耗品雑材自動計算 *0.03
    sumKigaku();            // 合計金額を下に表示
    bugakariCalculate();    // 歩掛再計算
    bugakariEvent();        // 歩掛合計と労務費を出す
    kingakuAndRom();        // 合計金額と労務費を足して見出しの金額に入れる
    taxGaku();              // 税込額算出
});


// 単価変更時、同じ資材IDの単価も変更する
$(document).on(
    "change",
    ".sizaiTanka, .zatuTankaCell, .keihiTankaCell, .kansetuTankaCell, .bottomTankaCell, .disTankaCell",
    async function (e) {
        const sizaiId = this.dataset.sizaiId;
        if (sizaiId !== '') {
            $('[data-sizai-id="' + sizaiId + '"]').val(this.value);
        }
        suKakeTanka();          // すべてのセルの 数量 × 単価
        await kansetuSet();
        kansetu_auto_calculate(); // 消耗品雑材自動計算 *0.03
        sumKigaku();            // 合計金額を下に表示
        kingakuAndRom();        // 合計金額と労務費を足して見出しの金額に入れる
        taxGaku();              // 税込額算出
    }
);

//すべてのセルの数量かける単価を金額に入れる
function suKakeTanka(){
    document.querySelectorAll('.suCell').forEach(element => {
            //数量セル
            const su=element;
            //単価セル
            const tanka=element.parentNode.nextElementSibling.nextElementSibling.children[0];
            //金額セル
            const gaku=element.parentNode.nextElementSibling.nextElementSibling.nextElementSibling.children[0];
            //計算
            gaku.value = Math.round(new BigNumber(Number(su.value)).multipliedBy(new BigNumber(Number(tanka.value))));
        });
}


//金額再計算
const sumGakuElement=document.querySelector('.sumGaku');
//金額の合計を下の合計行に入れる
function sumKigaku(){
    let sumGaku = 0;
    //gakuCell または zatuGakuCell
    document.querySelectorAll('.gakuCell , .zatuGakuCell , .keihiGakuCell, .kansetuGakuCell , .bottomGakuCell').forEach(element => {
        sumGaku+=parseInt(element.value);
    });
    document.querySelectorAll('.disGakuCell').forEach(element => {
        sumGaku-=parseInt(Math.abs(element.value));
    });
    sumGakuElement.innerHTML = sumGaku;
}

//合計金額と労務費を足して見出しの金額に入れる
function kingakuAndRom(){
    let sumGaku=parseInt(document.querySelector('.sumGaku').innerHTML);
    sumGaku+=Number(document.querySelector('.rom_gaku').value);
    document.querySelector("#mitumoriGakuHeader").innerHTML=sumGaku;
    taxGaku();
}

//消耗品雑材自動計算　金額の合計 * 0.03
function kansetu_auto_calculate(){
    if(document.querySelector('#kansetu_auto_calculate').checked==false) return;
    let sumGaku = 0;
    document.querySelectorAll('.gakuCell.bzai').forEach(element => {
        sumGaku+=parseInt(element.value);
    });
    sumGaku = new BigNumber(sumGaku);
    const zatuRate = new BigNumber(0.03);
    sumGaku=roundDecimal(sumGaku.multipliedBy(zatuRate).toString());
    
    if(document.querySelector('.zatuGakuCell')) document.querySelector('.zatuGakuCell').value = sumGaku;
    if(document.querySelector('.zatuTankaCell')) document.querySelector('.zatuTankaCell').value = sumGaku;
}



function bugakariCalculate(){
    document.querySelectorAll('.suCell').forEach(element => {
        const su= BigNumber(Number(element.value));
       //電工歩掛
        const bugakariDeTanka=BigNumber(Number(element.parentNode.parentNode.children[10].children[0].value));
        const sumBuDe=element.parentNode.parentNode.children[13].children[0]
        const tekkyo=BigNumber(sumBuDe.dataset.tekkyoRate);//普通特殊同じなので電工から撤去率を取得
        sumBuDe.value=tekkyo.multipliedBy(bugakariDeTanka.multipliedBy(su)).toString();
        
        //普通歩掛
        const bugakariTanka=BigNumber(Number(element.parentNode.parentNode.children[11].children[0].value));
        const sumBu=element.parentNode.parentNode.children[14].children[0];
        sumBu.value=tekkyo.multipliedBy(bugakariTanka.multipliedBy(su)).toString();
        
        //特殊歩掛
        const bugakariToTanka=BigNumber(Number(element.parentNode.parentNode.children[12].children[0].value));
        const sumBuTo=element.parentNode.parentNode.children[15].children[0];
        sumBuTo.value=tekkyo.multipliedBy(bugakariToTanka.multipliedBy(su)).toString();
        });
}


//間接費サーバーで処理後に返す
function kansetuSet(){
    return new Promise(function(resolve) {
    if(document.querySelector('#kansetu_auto_calculate').checked==false) return resolve();
    $.ajax({
            type : 'get',
            url : './kansetu/ajax?id='+mitumoriId,
            dataType : 'json',
            contentType : 'application/json; charset=UTF-8',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        }).done(function(response){
            for(v of response){
                if(document.querySelector('[data-kansetu-code="'+v.kansetu_code+'"]')){
                    document.querySelector('[data-kansetu-code="'+v.kansetu_code+'"]').value=v.tanka;
                    document.querySelector('[data-kansetu-code="'+v.kansetu_code+'"]')
                    .parentNode
                    .parentNode
                    .children[7].children[0].value=v.tanka;
                    
                };
            }
            
            resolve();
        }).fail(function(data){
            /* 通信失敗時 */
            console.log('残念');
        });
    })
}
//税込額算出
function taxGaku(){
    const sumGaku=BigNumber(document.querySelector("#mitumoriGakuHeader").innerHTML);
    const tax=BigNumber(document.querySelector("#tax_rate").value).multipliedBy(BigNumber(0.01)).plus(BigNumber(1));
    document.querySelector("#taxKomiGaku").innerHTML=Math.round(sumGaku.multipliedBy(tax).toString());
}
taxGaku();


//計算内容反映
async function addAutoRom(){
        let sumGaku=0;
        document.querySelectorAll(".bugakariGaku").forEach(element => {
            sumGaku+=parseInt(element.value);
        });
        document.querySelector(".rom_gaku").value=sumGaku;

        await mitumoriEdit(mitumoriId, "rom_gaku", sumGaku);
        await kansetuSet();
        sumKigaku();//合計金額を下に表示
        kingakuAndRom();
}

//一番下提出労務額を更新時に発動
async function romGakuChange(){
    await kansetuSet();
    sumKigaku();//合計金額を下に表示
    kingakuAndRom();
}

</script>


    
@endsection