@extends('layouts.app')
@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
<script src="{{asset('/js/jstree.min.js')}}"></script>

@include('mitumoriSetubiKani.hiroiM')

<style>

    .mitumori-header{
        background-color: rgb(98, 147, 159);
        color: black;
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

</style>

<div>
    <h1 class="text-2xl float-left mt-1">
        相簡易見積編集
    </h1>
    <form action="{{route('mitumoriSetubiKo.index')}}">
    <button type="submit" class="shadow-md shadow-cyan-950 btnStyle mt-1 mb-4 mr-3 float-right block text-white  focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-1 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
        戻る
    </button>
    <input type="hidden" id="mitumori" value="{{ $mitumori->mitumoriId }}" name="id"/>
    </form>
    <form action="{{route('mitumoriSetubiKani.aimitu',['id'=>$mitumori->mitumoriId,'companyId'=>$mitumori->companyId])}}" method="GET">
        <button class="shadow-md shadow-cyan-950 btnStyle mt-1 mb-4 mr-3 float-right block text-white  focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-1 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="submit">
            元見積から再算出
        </button>
        <input type="hidden" name="reStore" value="1">
    </form>
    <form target="_blank" action="{{route('mitumoriSetubi.pdf',['id'=>$mitumori->id,'companyId'=>$mitumori->companyId])}}" method="GET">
        <button class="shadow-md shadow-cyan-950 btnStyle mt-1 mb-4 mr-3 float-right block text-white  focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-1 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="submit">
            印刷
        </button>
        <select class="shadow-md shadow-cyan-950 px-4 py-1.5 text-xs mt-1 mb-4 mr-3 rounded-lg border-none w-[100px]  text-center float-right block" name="pdfType">
            <option value="1">PDF1</option>
            <option value="2">PDF2</option>
            <option value="3">PDF3</option>
        </select>
    </form>
</div>

<br>
<br>
<div class="flex mt-0">


<table class="float-left h-6 ml-2">
    <tr>
        <td nowrap class="text-center h-3 mitumori-header border border-slate-600 text-xs">担当者</td>
        <td class="h-3 w-30 border border-slate-600"><input name="tantoName" oninput="mitumoriEdit({{ $mitumori->id }},this.name,this.value)" class="text-xs border-none h-6 w-full p-1" type="text" value="{{ $mitumori->tantoName }}"></td>
        {{-- <td class="h-3 mitumori-header border border-slate-600 text-center  text-sm">施工日</td> --}}
        
        {{-- <td class="h-3 w-72 border border-slate-600"><input name="do_at" oninput="mitumoriEdit({{ $mitumori->id }},this.name,this.value)" class=" text-sm border-none h-6 w-72 p-1" type="date" value="{{ isset($mitumori->do_at)?carbon\carbon::parse($mitumori->do_at)->format('Y-m-d'):null }}"></td> --}}
        <td nowrap rowspan="2" class=" mitumori-header border border-slate-600 text-center text-sm">備考</td>
        <td rowspan="2" class=" border border-slate-600" style="padding:0px; max-height:60px!imporant; width:270px;">
            <textarea name="biko" oninput="mitumoriEdit({{ $mitumori->id }},this.name,this.value)" style="margin-bottom:-7px!important; max-height:57px; min-height:57px; width:100%; padding:0; margin:0;" class=" text-sm border-none">{{ $mitumori->biko }}</textarea>
        </td>
        
    </tr>
    <tr>
        <td class="mitumori-header border border-slate-600 text-xs">合計金額</td>
        <td class="h-6 w-32 border border-slate-600 text-right  pr-2 text-xs" id="mitumoriGakuHeader">
            {{$totalGaku}}
        </td>
        <td class="h-3 mitumori-header border border-slate-600 text-center  text-sm">見積日</td>
        <td class="h-3 w-72 border border-slate-600"><input name="print_at" oninput="mitumoriEdit({{ $mitumori->id }},this.name,this.value)" class=" text-sm border-none h-6 w-72 p-1" type="date" value="{{ isset($mitumori->print_at)?carbon\carbon::parse($mitumori->print_at)->format('Y-m-d'):null }}"></td>
        
    </tr>      
</table>



    
</div>

<div class="grid grid-flow-col gap-8 mt-5">
    <div class="col-span-8">
    <div class="overflow-y-auto" style="max-height: 60vh!important;">
        <table class="w-full">
            <thead class="sticky top-0">
                <tr>
                    <th class="mitumori-header text-xs border border-gray-400 w-[5%]  text-center">行No</th>
                    <th class="mitumori-header text-xs border border-gray-400 w-[18%]">品名</th>
                    <th class="mitumori-header text-xs border border-gray-400 w-[18%]">仕様</th>
                    <th class="mitumori-header text-xs border border-gray-400 w-[5%] ">数量</th>
                    <th class="mitumori-header text-xs border border-gray-400 w-[5%] ">単位</th>
                    <th class="mitumori-header text-xs border border-gray-400 w-[8%]">元単価</th>
                    <th class="mitumori-header text-xs border border-gray-400 w-[8%]">元金額</th>
                    <th class="mitumori-header text-xs border border-gray-400 w-[8%]">相見積単価</th>
                    <th class="mitumori-header text-xs border border-gray-400 w-[8%]">相見積金額</th>
                    <th class="mitumori-header text-xs border border-gray-400 w-[10%]">備考</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($mitumoriSais))
                    @foreach($mitumoriSais as $mS)
                    <tr>
                        <th class="bg-white text-xs border border-gray-400"><input style="overflow-y: hidden;" oninput="mitumoriSai(this.value,'{{$mS->id}}','gyoText')"  class="h-full text-center border-none w-full text-xs p-0 pr-1 pl-1 bg-gray-200" placeholder="{{ $mS->gyoNo }}" type="text" value="{{ $mS->gyoNo!=null?$mS->gyoText:'間接費' }}" @if($mS->gyoNo==null) disabled @endif></th><!--行ナンバー-->
                        <th class="bg-white text-xs border border-gray-400"><input style="overflow-y: hidden;" @disabled(true) class="h-full border-none w-full text-xs p-0 pr-1 pl-1  bg-gray-200 " type="text" value="{{ $mS->name }}" list="kozis" @if($mS->gyoNo==null) disabled @endif></th><!--品名-->
                        <th class="bg-white text-xs border border-gray-400"><input style="overflow-y: hidden;" @disabled(true) class="h-full border-none w-full text-xs p-0 pr-1 pl-1 bg-gray-200" type="text" value="{{ $mS->siyo }}"></th><!--仕様-->
                        <th class="bg-white text-xs border border-gray-400"><input style="overflow-y: hidden;" @disabled(true) class="h-full border-none suCell w-full text-xs p-0 pr-1 pl-1 text-right bg-gray-200" type="text" value="{{ $mS->su }}"></th><!--数量-->
                        <th class="bg-white text-xs border border-gray-400"><input style="overflow-y: hidden;" @disabled(true) class="h-full border-none w-full text-xs p-0 pr-1 pl-1 bg-gray-200" type="text" value="{{ $mS->tani }}"></th><!--単位-->
                        <th class="bg-white text-xs border border-gray-400"><input style="overflow-y: hidden;" @disabled(true) class="h-full border-none w-full text-xs p-0 pr-1 pl-1 text-right bg-gray-200" type="text" value="{{ abs($mS->moto_tanka) }}"></th><!--単価-->
                        <th class="bg-white text-xs border border-gray-400"><input style="overflow-y: hidden;" @disabled(true) class="h-full bg-gray-200 border-none w-full text-xs p-0 pr-1 pl-1 text-right" type="text" value="{{ abs($mS->moto_tanka)*$mS->su }}"></th><!--金額-->
                        <th class="bg-white text-xs border border-gray-400"><input style="overflow-y: hidden;" oninput="mitumoriSai(this.value,'{{$mS->id}}','tanka')" class="h-full border-none tankaCell w-full text-xs p-0 pr-1 pl-1 text-right" type="text" value="{{ $mS->hiyo_kbn==5?abs($mS->tanka):$mS->tanka }}" data-sizai-id="{{ $mS->tanka_rendo_code }}"></th><!--単価-->
                        <th class="bg-white text-xs border border-gray-400"><input style="overflow-y: hidden;" @disabled(true) class="h-full bg-gray-200 border-none {{ $mS->hiyo_kbn==5?'disGakuCell':'gakuCell' }} w-full text-xs p-0 pr-1 pl-1 text-right" type="text" value="{{ ($mS->hiyo_kbn==5?abs($mS->tanka):$mS->tanka)*$mS->su }}"></th><!--金額-->
                        <th class="bg-white text-xs border border-gray-400"><input style="overflow-y: hidden;" oninput="mitumoriSai(this.value,'{{$mS->id}}','biko')" class="h-full border-none w-full text-xs p-0 pr-1 pl-1" type="text" value="{{ $mS->biko }}"></th><!--備考-->
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    
    <table class="w-full table-cells">
            <tr>
                <td class="border-b border-gray-400 w-[5%]  text-center">合計</td>
                <td class="border-b border-gray-400 w-[18%]"></td>
                <td class="border-b border-gray-400 w-[18%]"></td>
                <td class="border-b border-gray-400 w-[5%] "></td>
                <td class="border-b border-gray-400 w-[5%] "></td>
                <td class="border-b border-gray-400 w-[8%]"></td>
                <td class="border-b border-gray-400 w-[8%]"></td>
                <td class="border-b border-gray-400 w-[8%] "></td>
                <td class="border-b border-gray-400 w-[8%] text-right text-xs pr-1 sumGaku" style="overflow: visible;">{{ (int)$totalGaku-(int)$mitumori->rom_gaku }}</td>
                <td class="border-b border-gray-400 w-[10%] "></td>
            </tr>
    </table>
        <div class="flex justify-end mt-0 text-right">
                労務費&nbsp;
                <input type="text" name="rom_gaku" onchange="mitumoriEdit({{ $mitumori->id }},this.name,this.value); sumHukugouGaku();" class="bugakariGaku bg-white w-32 placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded pl-1 py-0 transition duration-300 ease focus:outline-none focus:border-slate-600 hover:border-slate-600 shadow-sm focus:shadow-md appearance-none text-right" value="{{$mitumori->rom_gaku}}" />
                円
        </div>
    </div>
    
</div>

<button id="sizaiBtn" data-modal-target="hiroi-modal" data-modal-toggle="hiroi-modal"></button>

{{-- {{ $kozis }} --}}

<input type="text" id="kozi" name="kozi" list="kozis" class="hidden">


<script>


const bugakariGaku=document.querySelector('.bugakariGaku');
const mitumoriId="{{ $mitumori->id }}";


// 例: oninput から呼ばれる想定
let mitumoriTimer = null;
let mitumoriXhr = null;
let composing = null;

//mitumoriKoEdit関数
    function mitumoriEdit(mitumoriId,name,value) {
        // IME変換中は送らない（必要なら）
        if (composing) return;

        // デバウンス（400msは目安）
        clearTimeout(mitumoriSaiTimer);
        mitumoriSaiTimer = setTimeout(() => {
            // 直前の通信は中断
            if (mitumoriSaiXhr && mitumoriSaiXhr.abort) {
            mitumoriSaiXhr.abort();
            }

            mitumoriSaiXhr = $.ajax({
                type : 'put',
                url : 'onChange',
                dataType : 'json',
                data: {
                    id: mitumoriId,
                    name: name,
                    value: value
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                
            }).done(function(response){
                console.log(response);
            }).fail(function(xhr, textStatus, errorThrown){
            // デバッグしやすいログ
            console.error('[mitumoriSai fail]',
                'status=', xhr.status,
                'textStatus=', textStatus,
                'error=', errorThrown,
                'resp=', xhr.responseText);
            // ここで 419/422/500/parseerror 等の傾向がわかる
            });
        }, 400);
    };


// 例: oninput から呼ばれる想定
let mitumoriSaiTimer = null;
let mitumoriSaiXhr = null;
let composingSai = false; // IME対策（必要なら使う）

function mitumoriSai(v, id, name) {
  // IME変換中は送らない（必要なら）
  if (composingSai) return;

  // デバウンス（400msは目安）
  clearTimeout(mitumoriSaiTimer);
  mitumoriSaiTimer = setTimeout(() => {
    // 直前の通信は中断
    if (mitumoriSaiXhr && mitumoriSaiXhr.abort) {
      mitumoriSaiXhr.abort();
    }

    mitumoriSaiXhr = $.ajax({
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
      // 必要ならUI更新
      // console.log('saved', response);
    })
    .fail(function(xhr, textStatus, errorThrown){
     
    });
  }, 400);
}



const sizaiBtn = document.querySelector('#sizaiBtn');
const sizaiH = document.querySelector('#sizaiH');
const modalTable = document.querySelector('#modalTable');

let openCheck = false;


//単価変更時同じ資材も変更する
document.querySelectorAll(".tankaCell").forEach(function(e){
    e.addEventListener("change",async function(el){
        const sizaiId=el.target.dataset.sizaiId;
        if(sizaiId!=''){
            document.querySelectorAll('[data-sizai-id="'+sizaiId+'"]').forEach(function(el2){
                el2.value=el.target.value;
            })
        }
        suKakeTanka();
        sumGakuAgain();
    })
})

//単価変更イベント
function suKakeTanka(){
    document.querySelectorAll('.tankaCell').forEach(element => {
            //数量セル
            const su=element.parentNode.previousElementSibling.previousElementSibling.previousElementSibling.previousElementSibling.children[0];
            //単価
            const tanka=element;
            //金額セル
            const gaku=element.parentNode.nextElementSibling.children[0];
            //計算
            gaku.value = su.value * tanka.value;
    });
}

//金額再計算
const sumGakuElement=document.querySelector('.sumGaku');
function sumGakuAgain(){
    let sumGaku = 0;
    document.querySelectorAll('.gakuCell').forEach(element => {
        sumGaku+=parseInt(element.value);
    });
    document.querySelectorAll('.disGakuCell').forEach(element => {
        sumGaku-=parseInt(element.value);
    });
    sumGakuElement.innerHTML = sumGaku;
    sumHukugouGaku();
}



//合計金額と歩掛の金額を足す
function sumHukugouGaku(){
    const sumgaku=parseInt(document.querySelector('.sumGaku').innerHTML);
    let bugakariGaku=0;
    document.querySelectorAll('.bugakariGaku').forEach(element => {
        bugakariGaku+=parseInt(element.value);
    });

    document.querySelector('#mitumoriGakuHeader').innerHTML=sumgaku+bugakariGaku;
}







</script>


    
@endsection