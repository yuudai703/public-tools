@extends('layouts.appSchedule')
@section('content')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.3/themes/base/jquery-ui.css">


<!--<div style="background-color:red;" class="tableMarign">-->
<!--    aaaa-->
<!--</div>-->

<br/>

<style>

   body {
        font-family: 'ヒラギノ明朝 Pro W3', 'Hiragino Mincho Pro', '游明朝','Yu Mincho', '游明朝体', 'YuMincho','ＭＳ Ｐ明朝', 'MS PMincho', serif;
        min-height:100vh;
    }
    
    
    /* START TOOLTIP STYLES */
[tooltip] {
  position: relative; /* opinion 1 */
}

/* Applies to all tooltips */
[tooltip]::before,
[tooltip]::after {
  text-transform: none; /* opinion 2 */
  font-size: .9em; /* opinion 3 */
  line-height: 1;
  user-select: none;
  pointer-events: none;
  position: absolute;
  display: none;
  opacity: 0;
}
[tooltip]::before {
  content: '';
  border: 5px solid transparent; /* opinion 4 */
  z-index: 1001; /* absurdity 1 */
}
[tooltip]::after {
  content: attr(tooltip); /* magic! */
  
  /* most of the rest of this is opinion */
  font-family: Helvetica, sans-serif;
  text-align: center;
  
  /* 
    Let the content set the size of the tooltips 
    but this will also keep them from being obnoxious
    */
  /*min-width: 3em;*/
  width: 10em!important;
  white-space: normal;
  font-size:8px;
  /*white-space: nowrap;*/
  padding: 1ch 1.5ch;
  border-radius: .3ch;
  box-shadow: 0 1em 2em -.5em rgba(0, 0, 0, 0.35);
  background: #333;
  color: #fff;
  z-index: 1000; /* absurdity 2 */
}

/* Make the tooltips respond to hover */
[tooltip]:hover::before,
[tooltip]:hover::after {
  display: block;
}

/* don't show empty tooltips */
[tooltip='']::before,
[tooltip='']::after {
  display: none !important;
}

/* FLOW: UP */
[tooltip]:not([flow])::before,
[tooltip][flow^="up"]::before {
  bottom: 100%;
  border-bottom-width: 0;
  border-top-color: #333;
}
[tooltip]:not([flow])::after,
[tooltip][flow^="up"]::after {
  bottom: calc(100% + 5px);
}
[tooltip]:not([flow])::before,
[tooltip]:not([flow])::after,
[tooltip][flow^="up"]::before,
[tooltip][flow^="up"]::after {
  left: 50%;
  transform: translate(-50%, -.5em);
}

/* FLOW: DOWN */
[tooltip][flow^="down"]::before {
  top: 100%;
  border-top-width: 0;
  border-bottom-color: #333;
}
[tooltip][flow^="down"]::after {
  top: calc(100% + 5px);
}
[tooltip][flow^="down"]::before,
[tooltip][flow^="down"]::after {
  left: 50%;
  transform: translate(-50%, .5em);
}

/* FLOW: LEFT */
[tooltip][flow^="left"]::before {
  top: 50%;
  border-right-width: 0;
  border-left-color: #333;
  left: calc(0em - 5px);
  transform: translate(-.5em, -50%);
}
[tooltip][flow^="left"]::after {
  top: 50%;
  right: calc(100% + 5px);
  transform: translate(-.5em, -50%);
}

/* FLOW: RIGHT */
[tooltip][flow^="right"]::before {
  top: 50%;
  border-left-width: 0;
  border-right-color: #333;
  right: calc(0em - 5px);
  transform: translate(.5em, -50%);
}
[tooltip][flow^="right"]::after {
  top: 50%;
  left: calc(100% + 5px);
  transform: translate(.5em, -50%);
}

/* FX All The Things */ 
[tooltip]:not([flow]):hover::before,
[tooltip]:not([flow]):hover::after,
[tooltip][flow^="up"]:hover::before,
[tooltip][flow^="up"]:hover::after,
[tooltip][flow^="down"]:hover::before,
[tooltip][flow^="down"]:hover::after {
  animation: tooltips-vert 300ms ease-out forwards;
}

[tooltip][flow^="left"]:hover::before,
[tooltip][flow^="left"]:hover::after,
[tooltip][flow^="right"]:hover::before,
[tooltip][flow^="right"]:hover::after {
  animation: tooltips-horz 300ms ease-out forwards;
}

/* KEYFRAMES */
@keyframes tooltips-vert {
  to {
    opacity: .9;
    transform: translate(-50%, 0);
  }
}

@keyframes tooltips-horz {
  to {
    opacity: .9;
    transform: translate(0, -50%);
  }
}
@media screen and (min-width: 1600px){
    #tablediv{
        overflow-X: auto; 
        overflow-y: auto; 
        /*width: 1300px;*/
        /*max-height: 700px;*/
        max-width: 90vw;
        max-height: 80vh;
        /*border:1px solid black;*/
        /*padding-top:-50px!important;*/
    }
    .userName{
        white-space:nowrap;
        position: sticky; left: 0; z-index: 1;
        font-size: 13px;
        background-color: #FFC7AF!important;
        text-align: center;
        padding: 0px;
        min-width: 130px;
    }
    
    .day{
        min-width: 90px;
        text-align: center;
        padding: 0 0 28px 0 !important;
    }
    
    .event{
       
        margin: 0 0 2px 0;
        width: 88px;
        cursor:default;
        border-radius:10px;
        height: 25px;
        font-size: 15px;
        overflow: hidden;
        
    }
     /*見出しの日付*/
    .day-top{
        position: sticky; top: 0px; z-index: 2;
        pointer-events: none!important;
        height: 10px;
        text-align: center;
        background-color:#DDDDDD!important;
    }
    
    .monthButton{
        float: right; 
        margin-left: 20px;
    }
    
    .depaSelect{
        max-width: 210px;
        float: right; 
    }
    
    .ym{
        text-align:center; 
        margin-top: -5px;
        /*margin-bottom: px;*/
        font-size:24px;
        
    }
    
    .carousel-control-prev {
      height: 60px;
      width: 60px;
      top: 50%;
      left: 30px;
      transform: translateY(-50%);
      border-radius: 50%;
      background-color: #000;
    }
    
    .carousel-control-next {
      height: 60px;
      width: 60px;
      top: 50%;
      right: 30px;
      transform: translateY(-50%);
      border-radius: 50%;
      background-color: #000;
    }
    
    .img1{
            width:700px; 
            margin-left:42px;
            margin-top:-50px;
        }
    .img2{
            width:700px; 
            margin-left:50px;
            margin-top:-50px;
        }
    
    
}

@media screen and (max-width: 1600px) and (min-width: 769px) {
    #tablediv{
        overflow-X: auto; 
        overflow-y: auto; 
        /*width: 1100px;*/
        /*max-height: 500px;*/
        max-width: 100vw;
        max-height: 65vh;
        /*border:1px solid black;*/
        /*padding-top:-50px!important;*/
    }
    .userName{
        width: 130px;
        white-space:nowrap;
        position: sticky; left: 0; z-index: 1;
        font-size: 13px;
        background-color: #FFC7AF!important;
        text-align: center;
    }
    
    .day{
        min-width: 90px;
        text-align: center;
        padding: 0 0 28px 0 !important;
    }
    
    .event{
        margin: 0 0 2px 0;
        width: 88px;
        cursor:default;
        border-radius:10px;
        height: 25px;
        line-height:25px;
        font-size: 12px;
    }
     /*見出しの日付*/
    .day-top{
        position: sticky; top: 0px; z-index: 2;
        pointer-events: none!important;
        height: 5px;
        text-align: center;
        background-color:#DDDDDD!important;
       
    }
    
    .monthButton{
        float: right; 
        margin-left: 8px;
    }
    
    .depaSelect{
        max-width: 220px;
        float: right; 
    }
    
    .ym{
        /*float:left; */
        text-align:center;
        margin-top: 5px;
        margin-bottom: 0px;
        font-size:18px;
        
    }
    
    .carousel-control-prev {
      height: 60px;
      width: 60px;
      top: 50%;
      left: 30px;
      transform: translateY(-50%);
      border-radius: 50%;
      background-color: #000;
    }
    
    .carousel-control-next {
      height: 60px;
      width: 60px;
      top: 50%;
      right: 30px;
      transform: translateY(-50%);
      border-radius: 50%;
      background-color: #000;
    }
    
    .img1{
            width:700px; 
            margin-left:42px;
            margin-top:-50px;
        }
    .img2{
            width:700px; 
            margin-left:50px;
            margin-top:-50px;
        }
    
    
}

span {
  width:80px;
  text-align:center!important;
  display: block!important;
  padding-right:0px!important;
  padding-left:0px!important;
  font-size:12px!important;
  height:38px;
  padding-top: 8px!important;
 }
.address{
  font-size:12px!important;
}



.table-top{
        position: sticky; 
        top: 0px!important; 
        z-index: 3;
        position: sticky; left: 0; z-index: 3;
        text-align: center;
        background-color:#DDDDDD!important;
    }

.modaleOpen3{
    float:right; 
    border-radius:10px; 
    height:30px; 
    width:30px; 
    margin-top:5px; 
    margin-right:20px;
}

#carouselExample{
    width:770px;
}
    
    
    





/*//スマホ*/
@media screen and (max-width: 768px) {
    
    
    #tablediv{
        overflow-X: auto; 
        /*overflow-y: 70vh!important; */
        width: 100%;
        max-height: 75vh!important;
        padding:0px;
        margin:0px;
        /*position: relative;*/
        /*border:1px solid black;*/
        -webkit-overflow-scrolling: touch!important;
        /*background-color: gray!important;*/
    }
    
    .tableMask{
        height:30px!important;
        margin-top:0px!important;
        top:-1px!important;
        margin:0px;
        z-index: 2;
        /*background-color: red!important;*/
        /*display:none;*/
    }
    table{
        margin-top:0px!important;
        margin:0px;
        /*top:1250px!important;*/
         /*position: absolute;*/
        /*display:none;*/
        background-color: gray!important;
    }
    
    .userName{
        max-width: 60px;
        overflow: hidden;
        white-space:nowrap;
        position: sticky; left: 0; z-index: 1;
        font-size: 11px;
        background-color: #FFC7AF!important;
        padding:0;
        text-align:center;
        border: 1px solid black;
        text-align: center;
    }
    
    .day{
        min-width: 60px;
        text-align: center;
        padding: 0 0 28px 0 !important;
        height: 20px!important;
    }
    
    
    .table-top{
        position: sticky; 
        top: 0px!important; 
        z-index: 3;
        position: sticky; left: 0; z-index: 3;
        text-align: center;
        background-color:#DDDDDD!important;
    }
    
    /*見出しの日付*/
    .day-top{
        position: sticky; 
        /*position: fixed;*/
        top: 0px!important; 
        z-index: 2;
        pointer-events: none!important;
        height: 20px!important;
        text-align: center;
        background-color:#DDDDDD!important;
        font-size: 10px!important;
        padding-top:3px!important;
        padding-bottom:0px!important;
    }
    
    
    
    
    .event{
        user-select: none;
        margin: 0 0 2px 0;
        width: 58px;
        cursor:default;
        border-radius:10px;
        height: 25px;
        font-size:10px;
        line-height: 25px;
    }
    
    
    .monthButton{
        padding:4px;
        font-size: 12px;
        float: right; 
        margin-left: 8px;
    }
    
    .depaSelect{
        font-size: 12px;
        max-width: 160px;
        float: right; 
    }
    
    .ym{
        /*float:center; */
        text-align:center;
        margin-top: 5px;
        margin-bottom: 0px;
    }
    
    .img1,.img2{
        width:100%; 
        margin-left:5px;
        margin-top:-20px;
    }
    
    #carouselExample{
        width:100%;
    }
    
    /*.buttons{*/
    /*    margin-bottom:20px;*/
    /*    border:1px solid black;*/
    /*}*/
    
    .modaleOpen3{
        float:left; 
        border-radius:10px; 
        height:30px; 
        width:30px; 
        margin-right:20px;
        margin-top:1px;
    }
    
    .carousel-control-prev {
      height: 30px;
      width: 30px;
      top: 50%;
      left: 10px;
      transform: translateY(-50%);
      border-radius: 50%;
      background-color: #000;
    }
    
    .carousel-control-next {
      /*height: 30px;*/
      /*width: 30px;*/
      /*top: 50%;*/
      /*right: 30px;*/
      /*transform: translateY(-50%);*/
      /*border-radius: 50%!important;*/
      /*background-color: #000;*/
      
      height: 30px;
      width: 30px;
      top: 50%;
      right: 10px;
      transform: translateY(-50%);
      border-radius: 50%!important;
      background-color: #000;
    }
    
    
    /*二つ目のコンテキストメニュー*/
    #contextmenu p:nth-child(2),#contextmenu p:nth-child(3){
        margin-top:15px!important;
    }
    
    /*スマホでは見せない*/
    .printing{
        display:none;
    }
    
    
    
}
    
   
    
    
    .day:hover{
        background-color:#CCCCCC;
    }
    
    .selectable td.ui-selecting { background: #FECA40!important; }
    .selectable td.ui-selected { background: #F39814!important; color: white; }
    
    
    
    
    
    .event:hover {
        border:2px solid black;
    }
    .event {
        border:1px solid black;
        overflow:hidden;
    }
    
    td {
       border: 1px solid gray!important;
       margin-top: 0px;
    }
    
    
    
    
     #contextmenu,#contextmenu2,#contextmenu3,#contextmenu4,#contextmenu5,#contextmenu6{
                display:none;
                position:fixed;
                left:0px;
                top:0px;
                width:100px;
                max-height:120px;
                background-color:lightgray;
                opacity: 0.8;
                color: black;
                padding-top:10px;
                padding-bottom:10px;
            }
            #contextmenu p,#contextmenu2 p,#contextmenu3 p,#contextmenu4 p,#contextmenu5 p,#contextmenu6 p{
                cursor:pointer;
                List-style: none
                padding-left:26px;
                text-align:center;
                margin:0px;
                border-radius:20px;
            }
            #contextmenu p:nth-child(2),
            #contextmenu p:nth-child(3),
            #contextmenu2 p:nth-child(2),
            #contextmenu2 p:nth-child(3),
            #contextmenu4 p:nth-child(2),
            #contextmenu5 p:nth-child(2),
            #contextmenu6 p:nth-child(2){
                margin-top:10px;
            }
           
            #contextmenu p:hover,
            #contextmenu2 p:hover,
            #contextmenu4 p:hover,
            #contextmenu5 p:hover,
            #contextmenu6 p:hover,
            #contextmenu3 p:hover{
                color:white;
                background-color:blue;
            }
            
            
    .holidayTop{
        background-color:#FFFF80!important;
    }
    
    .holiday{
        background-color:#FFFFCC!important;
    }
    
    .todayTop{
        background-color:#B0E0E6!important;
    }
    .todayUser{
        background-color:#F0F8FF!important;
    }
    
    
    /*ユーザ追加*/
    .addUser{
        position:absolute; 
        z-index:3; 
        left:10px; 
        transform: scale(1.5);
    }
    
    .addUser:hover{
        color: blue;
    }
    
    /*印刷*/
    .printing{
        position:absolute; 
        z-index:3; 
        left:50px; 
        transform: scale(1.5);
    }
    .printing:hover{
        color: blue;
    }
    
    .userName[data-bs-target]{
        background-color:#DCC2FF!important; 
        cursor:default;
    }
    .userName[data-bs-target]:hover{
        background-color:#C299FF!important; 
    }
    
    
    

    
</style>

{!! Form::open(['route' => 'scheduleIndex','method'=>'get','id'=>"mainForm"]) !!}
<div>
    <button class='btn btn-secondary monthButton' name="addMonth" value="{{$ym->copy()->startOfMonth()->addMonth()->format('Y-m-d')}}">来月</button>
    <button class='btn btn-secondary monthButton' name="subMonth" value="{{$ym->copy()->subMonth()->format('Y-m-d')}}">先月</button>
    <select name="selectDepa" class='form-control depaSelect' onchange="depaSelect()">
        <option value="all">全ての部署</option>
        @forEach($depas as $dapa)
            <option value="{{$dapa->id}}" @if($selectdepa==$dapa->id) selected @endif >{{$dapa->name}}</option>
        @endforeach
    </select>
    <button onclick="helpImg()" type="button" class="modaleOpen3" style="" data-bs-toggle="modal" data-bs-target="#helpModal">?</button>
</div>
{!! Form::close() !!}

<br>
<div style="height:35px; margin-top:20px; position:relative;" class="alertDiv">
    <p class="printing" data-toggle="tooltip" tooltip="印刷" onclick="this.nextElementSibling.click()"><i class="bi bi-printer-fill"></i></p>
    <a class='d-none printingA' target="_blank" name="printing" href="{{asset('scheduleDayPailot/printing/'.$ym->copy()->format('Y-m-d').'/'.$selectdepa)}}"></a>
    <p class="addUser" data-toggle="tooltip" tooltip="社外メンバー登録" data-bs-toggle="modal" data-bs-target="#outUserModal"><i class="bi bi-person-fill-add"></i></p>
    <p class="ym" style="margin-bottom:-0px;">{{$ym->format('Y年m月')}}</p>
</div>

<div id="tablediv">
    <!--<div style="height:500px;">-->
    <!--<div class='tableMask' style="width: 100%; background-color: white; black; z-index: 2; margin-bottom: -20px;  position: sticky; top: 0px; z-index: 2;"></div>-->
    <table class='table'>
        <tr>
            <td class='table-top'>日</td>
            @for($i=1; $i<=$endDay; $i++)
                <td class="day-top @if(in_array($i,$holis)) holidayTop @endif @if($i==$today) todayTop @endif">{{$i}}<br>({{$dayOfWeek[($i+$weekNumber-1)%7]}})</td>
            @endfor
        </tr>
        <tbody class='selectable'>
        @foreach($users as $user)
        <tr class="userRow {{$depaArray[$user['id']]}} @if(strstr($depaArray[$user['id']],(string)$selectdepa)==false && $selectdepa!="all") d-none @endif">
            <td class="userName disable-selection non-selectable" @if(strpos($user['id'],"out")===0) data-bs-toggle="modal" data-bs-target="#outUserEditModal" onClick="outUserEdit('<?PHP echo $user["name"] ?>','<?PHP echo $depaArray[$user["id"]] ?>','<?PHP echo $user["id"] ?>')" @endif >{{$user["name"]}}</td>
            <!--アイホン使用時グラフィック下に行く原因-->
           
                
                <td onContextmenu="sellcontext(this)" class="{{'event-'.$user['id'].'-'.$dateYM.'-01'}} day sortable connectedSortable @if(in_array(1,$holis)) holiday @endif  @if(1==$today) todayUser @endif"></td>
                <td onContextmenu="sellcontext(this)" class="{{'event-'.$user['id'].'-'.$dateYM.'-02'}} day sortable connectedSortable @if(in_array(2,$holis)) holiday @endif  @if(2==$today) todayUser @endif"></td>
                <td onContextmenu="sellcontext(this)" class="{{'event-'.$user['id'].'-'.$dateYM.'-03'}} day sortable connectedSortable @if(in_array(3,$holis)) holiday @endif  @if(3==$today) todayUser @endif"></td>
                <td onContextmenu="sellcontext(this)" class="{{'event-'.$user['id'].'-'.$dateYM.'-04'}} day sortable connectedSortable @if(in_array(4,$holis)) holiday @endif  @if(4==$today) todayUser @endif"></td>
                <td onContextmenu="sellcontext(this)" class="{{'event-'.$user['id'].'-'.$dateYM.'-05'}} day sortable connectedSortable @if(in_array(5,$holis)) holiday @endif  @if(5==$today) todayUser @endif"></td>
                <td onContextmenu="sellcontext(this)" class="{{'event-'.$user['id'].'-'.$dateYM.'-06'}} day sortable connectedSortable @if(in_array(6,$holis)) holiday @endif  @if(6==$today) todayUser @endif"></td>
                <td onContextmenu="sellcontext(this)" class="{{'event-'.$user['id'].'-'.$dateYM.'-07'}} day sortable connectedSortable @if(in_array(7,$holis)) holiday @endif  @if(7==$today) todayUser @endif"></td>
                <td onContextmenu="sellcontext(this)" class="{{'event-'.$user['id'].'-'.$dateYM.'-08'}} day sortable connectedSortable @if(in_array(8,$holis)) holiday @endif  @if(8==$today) todayUser @endif"></td>
                <td onContextmenu="sellcontext(this)" class="{{'event-'.$user['id'].'-'.$dateYM.'-09'}} day sortable connectedSortable @if(in_array(9,$holis)) holiday @endif  @if(9==$today) todayUser @endif"></td>
                <td onContextmenu="sellcontext(this)" class="{{'event-'.$user['id'].'-'.$dateYM.'-10'}} day sortable connectedSortable @if(in_array(10,$holis)) holiday @endif @if(10==$today) todayUser @endif"></td>
                <td onContextmenu="sellcontext(this)" class="{{'event-'.$user['id'].'-'.$dateYM.'-11'}} day sortable connectedSortable @if(in_array(11,$holis)) holiday @endif @if(11==$today) todayUser @endif"></td>
                <td onContextmenu="sellcontext(this)" class="{{'event-'.$user['id'].'-'.$dateYM.'-12'}} day sortable connectedSortable @if(in_array(12,$holis)) holiday @endif @if(12==$today) todayUser @endif"></td>
                <td onContextmenu="sellcontext(this)" class="{{'event-'.$user['id'].'-'.$dateYM.'-13'}} day sortable connectedSortable @if(in_array(13,$holis)) holiday @endif @if(13==$today) todayUser @endif"></td>
                <td onContextmenu="sellcontext(this)" class="{{'event-'.$user['id'].'-'.$dateYM.'-14'}} day sortable connectedSortable @if(in_array(14,$holis)) holiday @endif @if(14==$today) todayUser @endif"></td>
                <td onContextmenu="sellcontext(this)" class="{{'event-'.$user['id'].'-'.$dateYM.'-15'}} day sortable connectedSortable @if(in_array(15,$holis)) holiday @endif @if(15==$today) todayUser @endif"></td>
                <td onContextmenu="sellcontext(this)" class="{{'event-'.$user['id'].'-'.$dateYM.'-16'}} day sortable connectedSortable @if(in_array(16,$holis)) holiday @endif @if(16==$today) todayUser @endif"></td>
                <td onContextmenu="sellcontext(this)" class="{{'event-'.$user['id'].'-'.$dateYM.'-17'}} day sortable connectedSortable @if(in_array(17,$holis)) holiday @endif @if(17==$today) todayUser @endif"></td>
                <td onContextmenu="sellcontext(this)" class="{{'event-'.$user['id'].'-'.$dateYM.'-18'}} day sortable connectedSortable @if(in_array(18,$holis)) holiday @endif @if(18==$today) todayUser @endif"></td>
                <td onContextmenu="sellcontext(this)" class="{{'event-'.$user['id'].'-'.$dateYM.'-19'}} day sortable connectedSortable @if(in_array(19,$holis)) holiday @endif @if(19==$today) todayUser @endif"></td>
                <td onContextmenu="sellcontext(this)" class="{{'event-'.$user['id'].'-'.$dateYM.'-20'}} day sortable connectedSortable @if(in_array(20,$holis)) holiday @endif @if(20==$today) todayUser @endif"></td>
                <td onContextmenu="sellcontext(this)" class="{{'event-'.$user['id'].'-'.$dateYM.'-21'}} day sortable connectedSortable @if(in_array(21,$holis)) holiday @endif @if(21==$today) todayUser @endif"></td>
                <td onContextmenu="sellcontext(this)" class="{{'event-'.$user['id'].'-'.$dateYM.'-22'}} day sortable connectedSortable @if(in_array(22,$holis)) holiday @endif @if(22==$today) todayUser @endif"></td>
                <td onContextmenu="sellcontext(this)" class="{{'event-'.$user['id'].'-'.$dateYM.'-23'}} day sortable connectedSortable @if(in_array(23,$holis)) holiday @endif @if(23==$today) todayUser @endif"></td>
                <td onContextmenu="sellcontext(this)" class="{{'event-'.$user['id'].'-'.$dateYM.'-24'}} day sortable connectedSortable @if(in_array(24,$holis)) holiday @endif @if(24==$today) todayUser @endif"></td>
                <td onContextmenu="sellcontext(this)" class="{{'event-'.$user['id'].'-'.$dateYM.'-25'}} day sortable connectedSortable @if(in_array(25,$holis)) holiday @endif @if(25==$today) todayUser @endif"></td>
                <td onContextmenu="sellcontext(this)" class="{{'event-'.$user['id'].'-'.$dateYM.'-26'}} day sortable connectedSortable @if(in_array(26,$holis)) holiday @endif @if(26==$today) todayUser @endif"></td>
                <td onContextmenu="sellcontext(this)" class="{{'event-'.$user['id'].'-'.$dateYM.'-27'}} day sortable connectedSortable @if(in_array(27,$holis)) holiday @endif @if(27==$today) todayUser @endif"></td>
                <td onContextmenu="sellcontext(this)" class="{{'event-'.$user['id'].'-'.$dateYM.'-28'}} day sortable connectedSortable @if(in_array(28,$holis)) holiday @endif @if(28==$today) todayUser @endif"></td>
                
                @if(isset($user["day29"]))
                    <td onContextmenu="sellcontext(this)" class="{{'event-'.$user['id'].'-'.$dateYM.'-29'}} day sortable connectedSortable @if(in_array(29,$holis)) holiday @endif @if(29==$today) todayUser @endif"></td>
                @endif
                
                @if(isset($user["day30"]))
                    <td onContextmenu="sellcontext(this)" class="{{'event-'.$user['id'].'-'.$dateYM.'-30'}} day sortable connectedSortable @if(in_array(30,$holis)) holiday @endif @if(30==$today) todayUser @endif"></td>
                @endif
                
                @if(isset($user["day31"]))
                    <td onContextmenu="sellcontext(this)" class="{{'event-'.$user['id'].'-'.$dateYM.'-31'}} day sortable connectedSortable @if(in_array(31,$holis)) holiday @endif @if(31==$today) todayUser @endif"></td>
                @endif
                
            <!-- -->
        </tr>
        @endforeach
        </tbody>
    </table>
    <!--</div>-->
</div>



<!-- Button trigger modal -->
<button type="button" class="btn btn-primary modaleOpen d-none" data-bs-toggle="modal" data-bs-target="#exampleModal">
</button>
@include("schedule.scheduleCreate")


<!-- Button trigger modal -->
<button type="button" class="btn btn-primary modaleOpen2 d-none" data-bs-toggle="modal" data-bs-target="#eventModal">
</button>
@include("schedule.scheduleShow")

<!--helpModal-->
@include("schedule.scheduleHelp")

<!--fullUpModal-->
@include("schedule.scheduleFullUp")

<!--outUserModal-->
@include("schedule.scheduleOutUser")

<!--outUserEditModal-->
@include("schedule.scheduleOutUserEdit")

        <!--//コンテキストメニュー-->
        <!--イベントclick-->
        <div id="contextmenu" class="shadow-lg rounded-4">
            <p onClick="menu1()">Copy</p>
            <p onClick="menu2()">削除</p>
            <p onClick="menu3()" data-bs-toggle="modal" data-bs-target="#fullUpModal">一括変更</p>
        </div>
        
        <!--セルをクリック-->
        <div id="contextmenu2" class="shadow-lg rounded-4">
            <p onClick="contextPest()">Paste</p>
            <p onClick="leave(this.innerHTML)">振替休日</p>
            <p onClick="leave(this.innerHTML)">有給休暇</p>
        </div>
        
        <!--イベントをクリック-->
        <div id="contextmenu3" class="shadow-lg rounded-4">
            <p class="leave_delete" onClick="menu2()"></p>
        </div>
        
        
        
        <!--振休があるセルをクリック-->
        <div id="contextmenu4" class="shadow-lg rounded-4">
            <p class="leave_delete1" onClick="leaveDelete('振替休日')">振休削除</p>
            <p class="leave_delete2" onClick="leaveDelete('有給休暇')">有給削除</p>
        </div>
        
        <input type="hidden" form="mainForm" class = "copyIndex" name="copyTitle" value="{{$copyArray['title']}}">
        <input type="hidden" form="mainForm" class = "copyIndex" name="copyContents" value="{{$copyArray['contents']}}">
        <input type="hidden" form="mainForm" class = "copyIndex" name="copyColor" value="{{$copyArray['color']}}">
        <input type="hidden" form="mainForm" class = "copyIndex" name="copyAddress" value="{{$copyArray['address']}}"> 

<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
	

<script>
    
    //グローバル変数
    const colorArray = ['black','darkblue','darkred','darkgreen','saddlebrown','red'];
    const yukyuControl = "<?PHP echo $yukyuControl?>";
    
    function helpImg(){
        document.querySelector(".img1").src="<?PHP echo asset('/storage/view_page-0001.jpg') ?>";
        document.querySelector(".img2").src="<?PHP echo asset('/storage/ui_page-0002.jpg') ?>";
    }
    
    
    
    function addressInput(e){
        const address= document.querySelector("option[value='"+e+"']").dataset.address;
        document.querySelector(".address").value=address;
    }
    
    
    const copyIndexfirst = document.querySelectorAll(".copyIndex");
    
    let copyModel=new Object({title:copyIndexfirst[0].value,contents:copyIndexfirst[1].value,color:copyIndexfirst[2].value,address:copyIndexfirst[3].value});
    console.log(copyModel);
    function modelCopyHaveing(){
        copyModel=new Object({title: '',contents: '',color: '',address: ''});
        const targetModel = document.querySelectorAll(".model-contents");
        copyModel.title=targetModel[0].innerHTML;
        copyModel.contents=targetModel[1].innerHTML;
        copyModel.color=targetModel[2].value;
        copyModel.address=targetModel[3].value;
        
        //hidden set value
        const copyIndex = document.querySelectorAll(".copyIndex");
        copyIndex[0].value=targetModel[0].innerHTML;
        copyIndex[1].value=targetModel[1].innerHTML;
        copyIndex[2].value=targetModel[2].innerHTML;
        copyIndex[3].value=targetModel[3].innerHTML;
        
        return copyModel;
    }
    
    //ペースト
    function pasteEvent(){
        if(copyModel.title != ''){
            const targetModel = document.querySelectorAll(".pasteTarget");
            targetModel[0].value=copyModel.title;
            targetModel[1].querySelector("option[value='"+copyModel.color+"']").selected=true;
            targetModel[2].value=copyModel.address;
            targetModel[3].value=copyModel.contents;
        }
    }
    
    
    //部署選択
    function depaSelect(){
        
        
        
        const depaSelect =document.querySelector('.depaSelect').value;
        
        //aタグ　print
        document.querySelector(".printingA").href="<?PHP echo asset('scheduleDayPailot/printing/'.$ym->copy()->format('Y-m-d')) ?>"+"/"+depaSelect;
        
        document.querySelectorAll('.userRow').forEach(function(e){
            e.classList.remove('d-none');
            if(!e.classList.contains('depa-'+depaSelect) && depaSelect!='all'){
                e.classList.add('d-none');
            }else if(e.classList.contains('depa-58') && depaSelect=='all'){
                e.classList.add('d-none');
            }
        });
        if(depaSelect!='all'){
            document.querySelector('#outUserDepa').value=depaSelect;
        }
        
    }
    
    
    //右クリ禁止
    // document.addEventListener('contextmenu',() => {
    // 	event.preventDefault();
    // });
    
    
    //イベント追加
    async function addEvent(){
        // const selectBox=document.querySelector('#anken');
        // const selecteAnken = selectBox.value;
        const selectDay = this.selectDay;
        const eventContents= document.querySelector("#eventContents").value;
        const name= document.querySelector(".anken_nm").value;
        const color= document.querySelector(".selectColor").value;
        const address= document.querySelector(".address").value;
        await insertData(this.selectDay,eventContents,name,color,address).done(function(data, status, xhr) {
            let dataI=0;
            
            const ankenName=document.querySelector(".anken_nm").value;
            const title_name = ankenName;
            const ankenColor = document.querySelector(".selectColor").value;
            
            for(let obj of selectDay) {
                const target=document.querySelector('.'+obj);
                target.innerHTML+="<div class='event' value='"+data[dataI]+"' style='background-color:"+ankenColor+";' onContextmenu='contextmenuAddEvent(this)' onClick='eventModal(this,"+data[dataI]+")'>"+title_name+"</div>";
                dataI++;
            }
            
            //最後に作業内容を消す
            document.querySelectorAll(".textContent").forEach(function(e){
                e.value="";
            });
        });
        //白テキスト
        textColor();
        document.querySelector("#closeBtn").click();
    }
    
    
    //モーダルウィンドウ開くのと同時にイベント追加
    let listenerObject;
    function modaleOpen(selectDay){
        if(listenerObject){
            document.querySelector("#submitBtn").removeEventListener('click', listenerObject, false);
        }
        document.querySelector('.modaleOpen').click();
        // 新しいリスナーオブジェクトを作成
        listenerObject = {
            selectDay: selectDay,
            handleEvent: addEvent
        };
        document.querySelector("#submitBtn").addEventListener('click',listenerObject,false);
    }
    
    
    function selectedRelease(){
        //万が一、編集画面がでていたら
        document.querySelectorAll('.ui-selected').forEach(function(e){
            e.classList.remove('ui-selected');
        })
        //最後に作業内容を消す
        document.querySelectorAll(".textContent").forEach(function(e){
            e.value="";
        });
    }
    
    
    
    function maskClick(){
        if(event.target.closest('.modal-content') === null) {
            selectedRelease();
        }
    }
    
    function maskClick2(){
        if(event.target.closest('.modal-content') === null) {
            setTimeout(()=>editClose(),500);
        }
    }
    
    function edetClose(){
        setTimeout(()=>editClose(),500);
    }
    
    
    //tabletop mask
    // function setWidht(){
    //     const tw = document.querySelector('table').offsetWidth + 10;
    //     console.log(tw);
    //     document.querySelector(".tableMask").style.width=tw+ "px";
    // }
    
    
    
    //最初に実行される
    window.onload = async function() {
        
        //イベントセット
        (await function () {
            const leave_events = <?PHP echo $leave_requests ?>;
            for(let obj of leave_events ) {
                const target=document.querySelector(obj.event);
                if(target){
                    let colorName;
                    switch (obj.name) {
                        case '振替休日': 
                            colorName='blue';
                            target.innerHTML+="<div class='event notMove' value='"+obj.id+"' style='color:"+colorName+"; border:none; font-weight: bold'  data-bs-toggle='tooltip' onContextmenu='contextmenuAddEvent(this)'>"+obj.name+"</div>";
                            break;
                            
                        case '有給休暇':
                            colorName='#FF4F02';
                            target.innerHTML+="<div class='event notMove' value='"+obj.id+"' style='color:"+colorName+"; border:none; font-weight: bold'  data-bs-toggle='tooltip' onContextmenu='contextmenuAddEvent(this)'>"+obj.name+"</div>";
                            break;
                        default:
                            colorName='black';
                            target.innerHTML+="<div class='event notMove' value='"+obj.id+"' style='color:"+colorName+"; border:none; font-weight: bold' data-bs-toggle='tooltip' data-bs-title='削除更新は勤怠画面から'>"+obj.name+"</div>";
                            break;
                    }
                }
            }
        }());
        
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
        
        
        
        //イベントセット
        (await function () {
            const user_events = <?PHP echo $events ?>;
            for(let obj of user_events ) {
                const target=document.querySelector(obj.event);
                if(target){
                    target.innerHTML+="<div class='event' value='"+obj.id+"' style='background-color:"+obj.color+";' onContextmenu='contextmenuAddEvent(this)' onClick='eventModal(this,"+obj.id+")'>"+obj.name+"</div>";
                }
            }
        }());
        
        
        //振替休日青
        textColor();
        
        //tooltipセット
        setTooltip();
        
       $( function() {
        //PCの時
        const width = window.innerWidth;
        if (width > 768) {
        
            //ドラッグ操作
            $( ".sortable" ).sortable({
                connectWith: ".connectedSortable",
                cancel: '.notMove',
                start: function(event, ui) {
                    ui.item.tooltip('hide');
                },
                drag: function(event, ui) {
                    ui.item.tooltip('hide');
                },
                stop: function(event, ui) {
                    ui.item.tooltip('hide');
                    var newParent = ui.item.parent();
                    moveData(ui.item.attr('value'),newParent[0].classList[0]);
                }
            }).disableSelection();
        
        
            //複数選択
            $( function() {
                $( ".selectable" ).selectable({
                    filter: 'td',
                    cancel: '.non-selectable',
                    selecting: function(event, ui) {
                        if ($(ui.selecting).hasClass('non-selectable')) {
                            $(ui.selecting).removeClass('ui-selecting');
                        }
                    },
                    
                    
                    stop: function() {
                        var result = $( "#select-result" ).empty();
                        let selectDay=[];
                        $( ".ui-selected", this ).each(function() {
                            if(this.classList[0]!="ui-selectee"){
                                selectDay.push(this.classList[0]);
                            }
                        });
            
                        console.log(this);
                        modaleOpen(selectDay);
                    }
                });
            } );
            
            document.querySelectorAll(".form-control").forEach(function(e){
                e.oncontextmenu = function () {return true;}
                console.log(e);
            });
            
        //スマホの時
        }else{
            document.querySelectorAll(".day").forEach(function(element){
                element.addEventListener("click",function(e){
                    if(event.target.closest('.event') === null) {
                        modaleOpen([this.classList[0]]);
                    }
                })
            })
        }
    })
    }
    
    
    
    
    //コンテキストメニュー イベントclick
    let menuElement
    function contextmenuAddEvent(el){
        if(el.innerHTML!="振替休日" && el.innerHTML!="有給休暇"){
            //表示させる
            document.getElementById('contextmenu').style.left=event.pageX+"px";//表示場所
            document.getElementById('contextmenu').style.top=event.pageY+"px";//表示場所
            document.getElementById('contextmenu').style.display="block";//表示させる
            document.getElementById('contextmenu2').style.display="none";
            document.getElementById('contextmenu3').style.display="none";
            document.getElementById('contextmenu4').style.display="none";
            
        }else{
            document.getElementById('contextmenu').style.display="none";
            document.getElementById('contextmenu2').style.display="none";
            document.getElementById('contextmenu4').style.display="none";
            document.getElementById('contextmenu3').style.left=event.pageX+"px";//表示場所
            document.getElementById('contextmenu3').style.top=event.pageY+"px";//表示場所
            document.getElementById('contextmenu3').style.display="block";//表示させる
            
            switch (el.innerHTML){
                case "振替休日":
                    document.querySelector('.leave_delete').innerHTML="振替削除";
                    break;
                case "有給休暇":
                    document.querySelector('.leave_delete').innerHTML="有給削除";
                    break;
            }
        }
       
        menuElement = el;
        
    }
    
    
    //消す
    document.body.addEventListener('click',function (e){
        document.getElementById('contextmenu').style.display="none";
        document.getElementById('contextmenu2').style.display="none";
        document.getElementById('contextmenu3').style.display="none";
        document.getElementById('contextmenu4').style.display="none";
    });
    
    
    
    
//=============================================
    // cellをクリック
    let pasteEl
    function sellcontext(e){
        //表示させる
        if(event.target.closest('.event') === null) {
            
            //セルをクリック振休有り無し
            let textArray=[];
            Array.from(event.target.children).forEach(function(e){
                textArray.push(e.innerHTML);
            })
            
            if(textArray.includes("振替休日",0)==false && textArray.includes("有給休暇",0)==false){
                document.getElementById('contextmenu').style.display="none";
                document.getElementById('contextmenu3').style.display="none";
                document.getElementById('contextmenu4').style.display="none";
                document.getElementById('contextmenu2').style.left=event.pageX+"px";//表示場所
                document.getElementById('contextmenu2').style.top=event.pageY+"px";//表示場所
                document.getElementById('contextmenu2').style.display="block";//表示させる
            }else{
                document.getElementById('contextmenu').style.display="none";
                document.getElementById('contextmenu2').style.display="none";
                document.getElementById('contextmenu3').style.display="none";
                document.getElementById('contextmenu4').style.left=event.pageX+"px";//表示場所
                document.getElementById('contextmenu4').style.top=event.pageY+"px";//表示場所
                document.getElementById('contextmenu4').style.display="block";//表示させる
                
                console.log(textArray.includes("振替休日",0) ,textArray.includes("有給休日",0));
                
                document.querySelector('.leave_delete1').style.display=textArray.includes("振替休日",0)== false ?'none':'block';
                document.querySelector('.leave_delete2').style.display=textArray.includes("有給休暇",0)== false ?'none':'block';
                //document.querySelector('.leave_delete3').style.display=textArray.includes("午前休",0)== false ?'none':'block';
            }
            pasteEl = e;
        }
    }
    
    //コンテキストメニュー貼付
    async function contextPest(){
        const arrayData=[pasteEl.classList[0]];
        if(copyModel.title != ''){
            await insertData(arrayData,copyModel.contents,copyModel.title,copyModel.color,copyModel.address).done(function(data, status, xhr) {
                pasteEl.innerHTML+="<div class='event' value='"+data[0]+"' style='background-color:"+copyModel.color+";' onContextmenu='contextmenuAddEvent(this)' onClick='eventModal(this,"+data[0]+")' >"+copyModel.title+"</div>";
            });
            textColor();
        }
    }
    
    //コンテキストメニュー振休または有
    async function leave(elContents){
        const arrayData=[pasteEl.classList[0]];
        await insertData(arrayData,'',elContents,'','').done(function(data, status, xhr) {
            
            if(data["message"]){
            
             alert(data["message"]);
            
            }else{
                let colorName
                switch (elContents) {
                    
                case '振替休日': 
                    colorName='blue';
                    break;
                    
                case '有給休暇':
                    colorName='#FF4F02';
                    break;
                    
                }
                pasteEl.innerHTML+="<div class='event notMove' value='"+data[0]+"' onContextmenu='contextmenuAddEvent(this)' style='color:"+colorName+"; border:none; font-weight: bold'>"+elContents+"</div>";
            }
        });
    }
    
    //コンテキストメニュー振休削除
    async function leaveDelete(elContents){
        //振替休日があるか確認
        
        if(yukyuControl == false && elContents=="有給休暇"){
            alert("当月の勤怠締切処理が実施されているため有給削除出来ません。");
        }else{
            Array.from(pasteEl.children).forEach(function(e){
                if(e.innerHTML==elContents){
                    deleteData(e.getAttribute('value'));
                    e.remove();
                }
            })
        }
        
    }
//=============================================
    
    //コンテキストメニューでコピー
    async function menu1(){
        const eid=menuElement.getAttribute("value");
        //ゲットメソッド
        await getData(eid).done(function(data, status, xhr) {
            const copydata = JSON.parse(data);
            console.log(copydata);
            copyModel=new Object({title: '',contents: '',color: '',address: ''});
            copyModel.title=copydata.title==undefined?'':copydata.title;
            copyModel.contents=copydata.contents;
            copyModel.color=copydata.color;
            copyModel.title_id=copydata.title_id;
            copyModel.address=copydata.address;
            
            //hidden set value
            const copyIndex = document.querySelectorAll(".copyIndex");
            copyIndex[0].value=copyModel.title;
            copyIndex[1].value=copyModel.contents;
            copyIndex[2].value=copyModel.color;
            copyIndex[3].value=copyModel.address;
            
        });
        return copyModel;
    }
    
    //コンテキストメニューでdelete
    async function menu2(){
        const eid=menuElement.getAttribute("value");
        
        //有給休暇操作できるか
        if(menuElement.innerHTML=="有給休暇" && yukyuControl == false){
            alert("当月の勤怠締切処理が実施されているため有給削除出来ません。");
        }else{
            deleteData(eid);
            console.log(menuElement);
            menuElement.remove();
        }
    }
    
    //コンテキストメニューで一括更新
    async function menu3(){
        const eid=menuElement.getAttribute("value");
        document.querySelector("#fullUpText").value=menuElement.innerHTML;
    }
    
    //modalで一括更新
    async function fullUpTitle(){
    
        // tableないのすべてのdiv要素を取得
        const mainTable=document.querySelector('table');
        const divs = mainTable.querySelectorAll('div');
        
        // テキストが'test'の要素をフィルタリング
        const targetHTML = menuElement.innerHTML;
        const newName = document.querySelector("#fullUpText").value;
        await divs.forEach(function(div) {
            if(div.innerText==targetHTML){
                div.innerText=newName;
            }
        });
        
        await updateData(menuElement.getAttribute("value"),newName,'fullTitleUpdate!!','','');
    }
    
    
    
    
    
    
    
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });
    
    
    
        
    //データ挿入
       function insertData(arrayData,eventContents,name,color,address) {
           
           console.log(document.querySelector(".selectColor").value);
           
            return $.ajax({
                        url: '/scheduleDayPailot/insert', 
                        type: "post", 
                        dataType: 'json',
                        contentType: "application/x-www-form-urlencoded; charset=UTF-8",
                        data:{
                            arrayData: arrayData,
                            eventContents: eventContents,//document.querySelector("#eventContents").value,
                            name: name,//document.querySelector(".anken_nm").value,
                            color: color,//document.querySelector(".selectColor").value,
                            address: address//document.querySelector(".address").value
                        },
                        processData: true,
                    });
            
        }
        
        //データゲット
       function getData(dataId) {
            return $.ajax({
                        url: '/scheduleDayPailot/get', 
                        type: "get", 
                        dataType: 'json',
                        contentType: "application/x-www-form-urlencoded; charset=UTF-8",
                        data:{
                            dataId: dataId
                        },
                        processData: true,
                    });
        }
        
        
    
    //データ削除
        function deleteData(dataId) {
            $.ajax({
                url: '/scheduleDayPailot/delete', 
                type: "delete", 
                dataType: 'json',
                contentType: "application/x-www-form-urlencoded; charset=UTF-8",
                data:{
                    dataId: dataId
                },
                processData: true,
            });
        }
        
    //データ操作
        function moveData(dataId,updatedYmd) {
            $.ajax({
                url: '/scheduleDayPailot/move', 
                type: "put", 
                dataType: 'json',
                contentType: "application/x-www-form-urlencoded; charset=UTF-8",
                data:{
                    dataId: dataId,//開始
                    updatedContents: updatedYmd
                },
                processData: true,
            });
        }
        
        //データ更新
        function updateData(dataId,name,color,address,eventContents) {
            //if color=fullTitleUpdate then is fullupdating!! 
            $.ajax({
                url: '/scheduleDayPailot/update', 
                type: "put", 
                dataType: 'json',
                contentType: "application/x-www-form-urlencoded; charset=UTF-8",
                data:{
                    dataId: dataId,
                    eventContents: eventContents,
                    name: name,
                    color: color,
                    address: address
                },
                processData: true,
            });
        } 
        
        
        
        //イベントclick
        window.editElement;//選択した要素を入れておく
        async function eventModal(e, eid){
            window.editElement = e;
            const showMap=document.querySelector(".showMap");
            showMap.src="";
            showMap.height="0px"
            //ゲットメソッド
            await getData(eid).done(function(data, status, xhr) {
                
                const showContents = JSON.parse(data);
                document.querySelector("#showContents").innerHTML=showContents.contents.replaceAll("\n","<br>");
                document.querySelectorAll(".model-contents")[2].value=showContents.color;
                document.querySelectorAll(".model-contents")[3].value=showContents.address;
                
                document.querySelectorAll(".edit-contents")[0].value=showContents.title
                document.querySelectorAll(".edit-contents")[1].value=showContents.color
                document.querySelectorAll(".edit-contents")[2].value=showContents.address
                document.querySelectorAll(".edit-contents")[3].value=showContents.contents;
                document.querySelector("#event-id").value=showContents.id;
                
                if(showContents.address!=""){
                    showMap.src="https://www.google.com/maps?output=embed&q="+showContents.address;
                    showMap.height="200px"
                }
            });
            
            //modal表示
            document.querySelector('.modaleOpen2').click();
            
            
            //前回のイベントを消す
            (function(){
            const clonedBtn = document.querySelector('#delteBtn').cloneNode(true);
            document.querySelector('#delteBtn').replaceWith(clonedBtn);
            })();
            
            
            //削除イベント追加
            document.querySelector('#delteBtn').addEventListener('click',function(){
                (async function(){
                    await deleteData(eid);
                    e.remove();
                    document.querySelector("#closeBtn2").click();
                }())
            });
            document.querySelector('#eventModalLabel').innerHTML=e.innerHTML;
        }
        
        function editUpdate(kbn){
            const editValue = document.querySelectorAll(".edit-contents");
            
                updateData(editElement.getAttribute("value"),editValue[0].value,editValue[1].value,editValue[2].value,editValue[3].value);
                var newElement = document.createElement('div');
                newElement.className = 'event';
                newElement.setAttribute('value', editElement.getAttribute("value"));
                newElement.style.backgroundColor = editValue[1].value;
                newElement.setAttribute('oncontextmenu', 'contextmenuAddEvent(this)');
                newElement.setAttribute('onclick', 'eventModal(this,' + editElement.getAttribute("value") + ')');
                newElement.innerHTML = editValue[0].value;
                
                // 既存の要素を新しい要素で置換
                editElement.replaceWith(newElement);
                setTimeout(()=>editClose(),500);
                
                //色
                textColor();
        }
        
        
        //編集画面＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝
        function editFormOpen(){
            if(event.target.innerHTML=="編集"){
                editOpen();
            }else{
                editClose();
            }
        }
        
        function editOpen(){
            document.querySelectorAll(".editContents").forEach(function(e){
                e.classList.remove("d-none");
            });
            document.querySelectorAll(".mainContents").forEach(function(e){
                e.classList.add("d-none");
            });
            document.querySelector(".editBtn").innerHTML="編集を閉じる"
        }
        
        function editClose(){
            document.querySelectorAll(".editContents").forEach(function(e){
                e.classList.add("d-none");
            });
            document.querySelectorAll(".mainContents").forEach(function(e){
                e.classList.remove("d-none");
            });
            document.querySelector(".editBtn").innerHTML="編集"
        }
        //＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝
        
        
        
        
        
        document.querySelectorAll(".eventsForms").forEach(function(e){
          e.addEventListener("focus",function(){
            this.previousElementSibling.style.backgroundColor="#678EFE";
            this.previousElementSibling.style.color="white";
            this.previousElementSibling.style.borderColor="#0033FF";
            console.log(this.previousElementSibling.backgroundColor);
          });
          e.addEventListener("blur",function(){
            this.previousElementSibling.style.backgroundColor="";
            this.previousElementSibling.style.borderColor="";
            this.previousElementSibling.style.color="black";
            console.log(this.previousElementSibling.backgroundColor);
          });
        });
        
        
        
        
        (function(){
            const width = window.innerWidth;
            if (width > 768) {
                let elm = document.querySelector("#tablediv");
                elm.scrollLeft = <?PHP echo $pivot?>;
            }else{
                let elm = document.querySelector("#tablediv");
                elm.scrollLeft = <?PHP echo round($pivot*0.67)?>;
            }
        })();
        
        
        //textcolor divだけレンダリング
        async function textColor(){
            // tableないのすべてのdiv要素を取得
            const mainTable=document.querySelector('table');
            const divs = mainTable.querySelectorAll('div');
            await divs.forEach(function(div) {
                
                //カラーの配列に入っているのか
                if(colorArray.includes(div.style.backgroundColor,0)){
                    div.style.color='white';
                }
            });
        }
        
        
        function setTooltip() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            //     tooltipTriggerEl.addEventListener('mousedown', function (e) {
            //         var tooltipInstance = bootstrap.Tooltip.getInstance(tooltipTriggerEl);
            //         if (tooltipInstance) {
            //             tooltipInstance.hide();
            //             console.log('Tooltip hidden');
            //         }
            //     });
            // });
        }
        
        
        //社外メンバー編集画面開いたとき
        function outUserEdit(name,depaId,userId){
            document.querySelector("#editOutUserName").value=name;
            document.querySelector("#outUserEditId").value=userId.replace('out','');
            document.querySelector("#editOutUserDepa").value=depaId.replace('depa-','');
        }
        
        
        
        
        
</script>

@endsection