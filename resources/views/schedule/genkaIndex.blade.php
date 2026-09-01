@extends('layouts.appKintai')
@section('content')
<style>
body {
        font-family: 'ヒラギノ明朝 Pro W3', 'Hiragino Mincho Pro', '游明朝','Yu Mincho', '游明朝体', 'YuMincho','ＭＳ Ｐ明朝', 'MS PMincho', serif;
    }

 td,th{
   border:1px solid black;
   text-align:center!important;
   font-size:13px;
 }
 
 th{
   background-color:#CCCCFF;
 }
 
 button.active {
    background-color:white!important;
    background: linear-gradient(to right, #8A2BE2, #DA70D6);
 }
 
.btn-right-radius {
  position: relative;
  display: inline-block;
  text-align:center;
  /*font-weight: bold;*/
  padding: 0.25em 0.5em;
  text-decoration: none;
  color: black;
  background: white;
  border-radius: 15px;
  transition: .4s;
  border: double 4px #67c5ff;
  cursor:default;
  margin-bottom:15px;
  width: 120px;
  float:left;
  margin-left:20px;
}

.btn-right-radius:hover {
  color:white;
  background: #712ef9;
  transform: scale(1.05);
}

.dataTarget{
 white-space:nowrap;
 width:50px;
}

.dataTarget:hover{
  background-color: #CCCCFF;
  color:white;
  cursor:default;
}
 
 
 span {
  width:80px;
  text-align:center!important;
  display: block!important;
  padding-right:0px!important;
  padding-left:0px!important;
 }
 .pills-tabContent{
  padding-top:0px!important;
 }
 
 table{
  margin:0px;
  padding:0px;
 }
 
 .nav{
 transform: scale(1);
  transition: transform 0.4s;
 }
 
 .nav:hover{
  transform: scale(1.05);
  transition: transform 0.4s;
 }
 
 .td0{
      width:60px;
      white-space:nowrap;
     }
 
 
 .td1{
  width:120px;
 }
 
 .td01{
  width:180px;
 }
 
 .td2{
  width:360px;
 }
 
 .td3{
  width:220px;
  text-align:right!important;
 }
 
 .td4{
 
 }
 
 .sumTable{
  font-size:14px; 
  width:320px; 
  position:absolute; 
  right:0px; 
  top:0px; 
  margin-bottom:20px;
 }
 
 .ankenTitle{
  font-size:20px; 
  width:320px; 
  position:absolute; 
  left:0px; 
  top:10px; 
  margin-bottom:20px;
 }
 
 #backBtn{
  margin-bottom:17px;
 }
 
  /*//スマホ*/
  @media screen and (max-width: 768px) {
    td,th{
       font-size:10px;
    }
    .td0{
      width:60px;
      white-space:nowrap;
     }
    
    
    .td1{
      width:60px;
      white-space:nowrap;
     }
     
     .td2{
      width:100px;
      white-space:nowrap
     }
     
     .td3{
      width:70px;
      text-align:right!important;
      white-space:nowrap;
      padding-right: 10px!important;
     }
     
     .td4{
     
     }
     
     li>button {
      font-size:10px!important;
      width:70px!important;
     }
     ul{
      width:283px!important;
     }
      
  }
</style>

<br>
<div style="position:relative; height:80px;">
<p class="ankenTitle">案件名：{{$target->name}}</p>
<div style="display:flex; justify-content:center;">
<ul class="nav nav-pills mb-3 shadow" id="pills-tab" role="tablist" style="width:403px; border:1px solid #6600CC; border-radius: 20px;">
  <li class="nav-item" role="presentation">
    <button
      class="nav-link @if(!in_array(session('dt_kbn'),[2,3,4])) active @endif"
      id="pills-home-tab"
      data-bs-toggle="pill"
      data-bs-target="#pills-home"
      type="button"
      role="tab"
      aria-controls="pills-home"
      aria-selected="true"
      style="width:100px; border-radius: 20px;"
      onClick="activeNav(1)"
    >
      材料費
    </button>
  </li>
  <li class="nav-item" role="presentation">
    <button
      class="nav-link @if(session('dt_kbn')==2) active @endif"
      id="pills-profile-tab"
      data-bs-toggle="pill"
      data-bs-target="#pills-profile"
      type="button"
      role="tab"
      aria-controls="pills-profile"
      aria-selected="false"
      style="width:100px; border-radius: 20px;"
      onClick="activeNav(2)"
    >
      外注費
    </button>
  </li>
  <li class="nav-item" role="presentation">
    <button
      class="nav-link @if(session('dt_kbn')==3) active @endif"
      id="pills-contact-tab"
      data-bs-toggle="pill"
      data-bs-target="#pills-contact"
      type="button"
      role="tab"
      aria-controls="pills-contact"
      aria-selected="false"
      style="width:100px; border-radius: 20px;"
       onClick="activeNav(3)"
    >
      経費
    </button>
  </li>
  <li class="nav-item" role="presentation">
    <button
      class="nav-link @if(session('dt_kbn')==4) active @endif"
      id="pills-romu-tab"
      data-bs-toggle="pill"
      data-bs-target="#pills-romu"
      type="button"
      role="tab"
      aria-controls="pills-romu"
      aria-selected="false"
      style="width:100px; border-radius: 20px;"
      onClick="activeNav(4)"
    >
      労務費
    </button>
  </li>
</ul>
</div>

<!--<div style="font-size:14px; width:320px; position:absolute; right:0px; top:0px; margin-bottom:20px;">-->
<!--  <div style="border:1px solid black;display:flex;"><span style="text-align:right!important; width:80px;">材料費合計:</span><span style="text-align:right!important; width:220px;">円</span></div>-->
<!--  <div style="border:1px solid black;display:flex;"><span style="text-align:right!important; width:80px;">外注費合計:</span><span style="text-align:right!important; width:220px;">円</span></div>-->
<!--  <div style="border:1px solid black;display:flex;"><span style="text-align:right!important; width:80px;">経費合計:</span><span style="text-align:right!important; width:220px;">円</span></div>-->
<!--  <div style="border:1px solid black;display:flex;"><span style="text-align:right!important; width:80px;">労務費合計:</span><span style="text-align:right!important; width:220px;">円</span></div>-->
<!--  <div style="border:1px solid black;display:flex;"><span style="text-align:right!important; width:80px;">総合計:</span><span style="text-align:right!important; width:220px;">円</span></div>-->
<!--</div>-->

<table class="sumTable" style="">
  <tr>
    <td style="white-space: nowrap;"><span style="text-align:right!important; width:80px; display:inline-block!important;">材料費合計:</span><span style="text-align:right!important; width:220px; display:inline-block!important;">{{number_format($sumZai)}}&nbsp;&nbsp;円</span></td>
  </tr>
  <tr>
    <td style="white-space: nowrap;"><span style="text-align:right!important; width:80px; display:inline-block!important;">外注費合計:</span><span style="text-align:right!important; width:220px; display:inline-block!important;">{{number_format($sumGai)}}&nbsp;&nbsp;円</span></td>
  </tr>
  <tr>
    <td style="white-space: nowrap;"><span style="text-align:right!important; width:80px; display:inline-block!important;">経費合計:</span><span style="text-align:right!important; width:220px; display:inline-block!important;">{{number_format($sumKei)}}&nbsp;&nbsp;円</span></td>
  </tr>
  <tr>
    <td style="white-space: nowrap;"><span style="text-align:right!important; width:80px; display:inline-block!important;">労務費合計:</span><span style="text-align:right!important; width:220px; display:inline-block!important;">{{number_format($sumRomu)}}&nbsp;&nbsp;円</span></td>
  </tr>
  <tr>
    <td style="white-space: nowrap;"><span style="text-align:right!important; width:80px; display:inline-block!important;">総合計:</span><span style="text-align:right!important; width:220px; display:inline-block!important;">{{number_format($sumZai+$sumGai+$sumKei+$sumRomu)}}&nbsp;&nbsp;円</span></td>
  </tr>
</table>


</div>

{!!link_to_route("ankenCreate","戻る",[],["id"=>"backBtn","style"=>"float:left;","class"=>"btn btn-light border","onclick"=>"history.back()"])!!}
<button type="button" style="z-index:2;" class="btn-right-radius" id="modal2" data-bs-toggle="modal" data-bs-target="#exampleModal">新規登録</button>
<div class="tab-content" id="pills-tabContent" style="">
  <div
    class="tabs @if(in_array(session('dt_kbn'),[2,3,4])) d-none @endif">
    <table style="width:100%;" class="table-hovered">
      <tr>
        <th class="td0"></th>
        <th class="td1">日付</th>
        <th class="td2">仕入先名</th>
        <th class="td3" style="text-align:center!important;">金額（税込）</th>
        <th class="td4">備考</th>
      </tr>
        @forEach($zais as $zai)
        <tr>
          <td class="dataTarget" onclick="editModal('1',{{json_encode($zai)}})" nowrap data-bs-toggle="modal" data-bs-target="#editModal">選択</td>
        　<td class="td1">{{$zai->target_at}}</td>
        　<td class="td2">{{$zai->torisaki_nm}}</td>
        　<td class="td3">{{number_format($zai->gaku)}}&nbsp;円&nbsp;</td>
        　<td class="td4">{{$zai->biko}}</td>
        </tr>
      @endforEach
    </table>
  </div>
  <div
    class="tabs @if(session('dt_kbn')!=2) d-none @endif"
    style=""
    >
    <table style="width:100%;">
      <tr>
        <th class="td0"></th>
        <th class="td1">日付</th>
        <th class="td2">外注先名</th>
        <th class="td3" style="text-align:center!important;">金額（税込）</th>
        <th class="td4">備考</th>
      </tr>
      @if(isset($gais))
        @forEach($gais as $gai)
        <tr>
          <td class="dataTarget" onclick="editModal('2',{{json_encode($gai)}})"  nowrap data-bs-toggle="modal" data-bs-target="#editModal">選択</td>
        　<td class="td1">{{$gai->target_at}}</td>
        　<td class="td2">{{$gai->torisaki_nm}}</td>
        　<td class="td3">{{number_format($gai->gaku)}}&nbsp;円&nbsp;</td>
        　<td class="td4">{{$gai->biko}}</td>
        </tr>
        @endforEach
      @endif
    </table>
  </div>
  <div
    class="tabs @if(session('dt_kbn')!=3) d-none @endif">
    <table style="width:100%;">
      <tr>
      　<th class="td0"></th>
        <th class="td1">日付</th>
        <th class="td2">相手先名</th>
        <th class="td3" style="text-align:center!important;">金額（税込）</th>
        <th class="td4">備考</th>
      </tr>
      @if(isset($keis))
        @forEach($keis as $kei)
        <tr>
          <td class="dataTarget" onclick="editModal('3',{{json_encode($kei)}})"  nowrap data-bs-toggle="modal" data-bs-target="#editModal">選択</td>
        　<td class="td1">{{$kei->target_at}}</td>
        　<td class="td2">{{$kei->torisaki_nm}}</td>
        　<td class="td3">{{number_format($kei->gaku)}}&nbsp;円&nbsp;</td>
        　<td class="td4">{{$kei->biko}}</td>
        </tr>
        @endforEach
      @endif
    </table>
  </div>
  <div class="tabs @if(session('dt_kbn')!=4) d-none @endif">
    <table style="width:100%;">
      <tr>
        <th class="td01">日付</th>
        <th class="td2">詳細</th>
        <th class="td3" style="text-align:center!important;">金額（税込）</th>
        <th class="td4">備考</th>
      </tr>
      @if(isset($romus))
        @forEach($romus as $romu)
        <tr>
          <td class="td1">{{$romu->target_at}}</td>
          <td class="td2" style="text-align:center!important;">
            @if($romu->Agaku!=0)
              {{'35000'.'×'.($romu->Agaku/35000)}}
           @endif
            @if($romu->Agaku!=0 && $romu->Bgaku!=0)
                &nbsp;&nbsp;&nbsp;
           @endif
           @if($romu->Bgaku!=0)
             {{'30000'.'×'.($romu->Bgaku/30000)}}
           @endif</td>
          <td class="td3">{{number_format($romu->gaku)}}&nbsp;円&nbsp;&nbsp;&nbsp;&nbsp;</td>
          <td class="td4">@if(isset($userName[$romu->target_at])){{$userName[$romu->target_at]}} @endif</td>
        </tr>
        @endforEach
      @endif
    </table>
  </div>
</div>





<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header create-modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      {!! Form::open(['route'=>['genkaStore','id'=>$ankenId], 'method' => 'post','id'=>'main_form']) !!}
        <div class="input-group inputText" style="margin-top:10px; text-align:center;">
          <span class="input-group-text createOrEditSpan createColorBorder" id="target_at">日付</span>
          <input type="date" class="form-control ankenForms" name="target_at" value="{{carbon\carbon::today()->format('Y-m-d')}}" max="9999-12-31"/>
        </div>
        <div class="input-group inputText" style="margin-top:10px;">
          <span class="input-group-text createOrEditSpan createColorBorder" id="torisaki_nm">仕入先</span>
          <input type="text" class="form-control ankenForms" name="torisaki_nm"/>
        </div>
        <div class="input-group inputText" style="margin-top:10px;">
          <span class="input-group-text createOrEditSpan createColorBorder" id="gaku">金額</span>
          <input type="text" class="form-control ankenForms" name="gaku"/>
        </div>
        <div class="input-group inputText" style="margin-top:10px;">
          <span class="input-group-text createOrEditSpan createColorBorder" id="biko">備考</span>
          <input type="text" class="form-control ankenForms" name="biko"/>
        </div>
        <input type="hidden" id="dt_kbn" name="dt_kbn" value="{{session('dt_kbn')??1}}"/>
      {!! Form::close() !!}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" form="main_form">追加</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header create-modal-header">
        <h5 class="modal-title" id="editModalLabel">編集</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        {!! Form::open(['route'=>'genkaUpdate', 'method' => 'put','id'=>'edit_form']) !!}
          <div class="input-group inputText" style="margin-top:10px; text-align:center;">
            <span class="input-group-text createOrEditSpan createColorBorder" id="target_at">日付</span>
            <input type="date" id="editTarget_at" class="form-control ankenForms" name="target_at" max="9999-12-31"/>
          </div>
          <div class="input-group inputText" style="margin-top:10px;">
            <span class="input-group-text createOrEditSpan createColorBorder" id="torisaki_nm">仕入先</span>
            <input type="text" id="editTorisaki_nm" class="form-control ankenForms" name="torisaki_nm"/>
          </div>
          <div class="input-group inputText" style="margin-top:10px;">
            <span class="input-group-text createOrEditSpan createColorBorder" id="gaku">金額</span>
            <input type="text" id="editGaku" class="form-control ankenForms" name="gaku"/>
          </div>
          <div class="input-group inputText" style="margin-top:10px;">
            <span class="input-group-text createOrEditSpan createColorBorder" id="biko">備考</span>
            <input type="text" id="editBiko" class="form-control ankenForms" name="biko"/>
          </div>
          <input type="hidden" id="edit_dt_kbn" name="dt_kbn"/>
          <input type="hidden" id="edit_id" name="id"/>
        {!! Form::close() !!}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        {!! Form::open(['route'=>'genkaDelete', 'method' => 'delete','id'=>'delete_form']) !!}
          <input type="hidden" id="delete_id" name="id"/>
          <button type="submit" class="btn btn-danger" form="delete_form">削除</button>
        {!! Form::close() !!}
        <button type="submit" class="btn btn-success" form="edit_form">更新</button>
      </div>
    </div>
  </div>
</div>


<script>

function editModal(contents_kbn,e){
  
  document.querySelector("#editTarget_at").value=e.target_at;  
  document.querySelector("#editGaku").value=e.gaku;  
  document.querySelector("#editBiko").value=e.biko;
  document.querySelector("#editTorisaki_nm").value=e.torisaki_nm;
  document.querySelector("#edit_dt_kbn").value=e.dt_kbn;
  document.querySelector("#edit_id").value=e.id;
  document.querySelector("#delete_id").value=e.id;
  
  if(contents_kbn==1){
    document.querySelector("#editTorisaki_nm").previousElementSibling.innerHTML="仕入先名";  
  }else if(contents_kbn==2){
    document.querySelector("#editTorisaki_nm").previousElementSibling.innerHTML="外注先名";  
  }else{
    document.querySelector("#editTorisaki_nm").previousElementSibling.innerHTML="相手先名";  
  }
}



document.querySelectorAll(".ankenForms").forEach(function(e){
  e.addEventListener("focus",function(){
    this.previousElementSibling.style.backgroundColor="#712ef9";
    this.previousElementSibling.style.color="white";
    this.previousElementSibling.style.borderColor="#660066";
    console.log(this.previousElementSibling.backgroundColor);
  });
  e.addEventListener("blur",function(){
    this.previousElementSibling.style.backgroundColor="";
    this.previousElementSibling.style.borderColor="";
    this.previousElementSibling.style.color="black";
    console.log(this.previousElementSibling.backgroundColor);
  });
});


function activeNav(i){
  document.querySelector("#dt_kbn").value=i;
  const torisaki_nm =document.querySelector("#torisaki_nm");
  const title =document.querySelector("#exampleModalLabel");
  const tabs =document.querySelectorAll(".tabs");
  if(i==1){
    torisaki_nm.innerHTML="仕入先名"
    title.innerHTML="材料費登録"
    tabs[0].classList.remove("d-none");
    tabs[1].classList.add("d-none");
    tabs[2].classList.add("d-none");
    tabs[3].classList.add("d-none");
    document.querySelector("#modal2").classList.remove("d-none");
  }else if(i==2){
    torisaki_nm.innerHTML="外注先名"
    title.innerHTML="外注費登録"
    tabs[0].classList.add("d-none");
    tabs[1].classList.remove("d-none");
    tabs[2].classList.add("d-none");
    tabs[3].classList.add("d-none");
    document.querySelector("#modal2").classList.remove("d-none");
  }else if(i==3){
    torisaki_nm.innerHTML="相手先名"
    title.innerHTML="経費登録"
    tabs[0].classList.add("d-none");
    tabs[1].classList.add("d-none");
    tabs[2].classList.remove("d-none");
    tabs[3].classList.add("d-none");
    document.querySelector("#modal2").classList.remove("d-none");
  }else if(i==4){
    torisaki_nm.innerHTML="従業員名"
    title.innerHTML="労務費登録"
    tabs[0].classList.add("d-none");
    tabs[1].classList.add("d-none");
    tabs[2].classList.add("d-none");
    tabs[3].classList.remove("d-none");
    document.querySelector("#modal2").classList.add("d-none");
  }
}

</script>

@endsection