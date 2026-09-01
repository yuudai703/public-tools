@extends('layouts.appKintai')
@section('content')

<style>
  body {
        font-family: "YuGothic","Yu Gothic","Meiryo","ヒラギノ角ゴ","sans-serif";
    }
    
   .createOrEditSpan{
      width:100px;
    }

  .card-text{
    text-align:left;
  }
  
  .shadow{
    transition: transform 0.3s;
  }
  .shadow:hover{
    transform: scale(1.05);
  }
  
  .sm-text{
    font-size: 12px;
    width:63px;
  }
  
  
  #depaSearch{
    position: absolute; 
    left:160px; 
    top:25px; 
    width: 150px;
  }
  
   #dateInput{
      position: absolute; 
      left:320px; 
      top:25px; 
      width: 150px;
    }
    
  .showBottomContents{
      display: flex; 
      /*overflow: hidden;*/
    }
  .canvasCart{
    width:600px; 
    height:300px; 
    margin-left:-90px; 
    position :relative;
  }
  
  .tableDiv{
    width: 200px; 
    padding-top: 30px;
    padding-left: -130px;
    z-index:2px;
  }
  
  .position-table{
    position :absolute; 
    overflow-y: auto;
    height: 280px;
    left:510px; 
    top: 25px;
    width: 270px;
  }
  
  .canvas-container .chartjs-axis-label {
      font-size: 10px; /* フォントサイズを小さくする */
  }

  
  /*//スマホ*/
  @media screen and (max-width: 768px) {
    
    #dateInput{
      width: 48%!important;
      position: absolute; 
      left:0px; 
      margin-top: 40px;
      margin-right: 10px;
      float:left;
      /*top:50px; */
      width: 47%;
      z-index:1;
    }
    
    .codeSearch{
      margin-top: 40px;
      /*width: 99%!important;*/
      width: 48%!important;
      
    }
    
    .row-cols-md-4{
      margin-top: 60px;
    }
    
    #depaSearch{
      margin-right: 10px;
      width: 48%!important;
      position: absolute; 
      left:50%; 
      float:left;
      z-index:1;
      margin-top: 40px;
      /*top:25px; */
      width: 47%;
    }
    
    .showBottomContents{
      display: block; 
      /*overflow: hidden;*/
    }
    
    .canvasCart{
    width:600px; 
    height:300px;
    
    margin-left:-130px;
    /*display:flex;*/
    /*justify-content:center;*/
    
    /*position :relative;*/
  }
  
  .tableDiv{
    width: 200px; 
    padding-top: 30px;
  }
  
  .position-table{
    position :absolute; 
    left:21%; 
    /*right:10%; */
    top: 320px;
    width: 55%;
  }
  
  .modal-body{
    overflow-y: auto;
    overflow-x: hidden;
    transform: scale(1);
   }
   
    
    
    /*//スマホ*/
    @media screen and (min-width: 389px) {
       .modal-body{
          /*background-color:red;*/
          margin-left:10px;
         }
      
    }
    
    /*//スマホ*/
    @media screen and (min-width: 410px) {
       .modal-body{
          /*background-color:red;*/
          margin-left:20px;
         }
      
    }
    
    
     /*//スマホ*/
    @media screen and (max-width: 361px) {
       .position-table{
          /*background-color:red;*/
          width: 52%;
          left:23%;
         }
      
    }
  }

  
  
</style>
<div style="display:flex; justify-content: center; position: relative;">
  
  <input type="text" class="form-control codeSearch" id="codeSearch" placeholder="コード検索" style="position: absolute; left:0; top:25px; width: 150px;" onInput="ankenSearch()">
  <select class="form-control" id="depaSearch" name="depa" onInput="ankenSearch()">
      <option class="editDepa" value="all">全て</option>
      @foreach($depas as $depa)
          <option class="editDepa" value="{{$depa->id}}">{{$depa->name}}</option>
      @endforeach
  </select>
  
  <h2 style="margin-top: 20px; margin-bottom: 50px; ">案件一覧</h2>
  <button type="button" style="position: absolute; right:0; top:20px;" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#exampleModal">新規登録</button>
  <button type="button" class="btn d-none" id="modal2" data-bs-toggle="modal" data-bs-target="#exampleModal2">新規登録</button>
</div>
<div class="row row-cols-1 row-cols-md-4 g-4">
  @forEach($ankens as $anken)
      <div class="col ankenCard" data-code="{{$anken->code}}" data-depa="{{$anken->depa_id}}" onClick="ankenShow({{json_encode($anken)}})">
        <div class="card h-100 shadow">
          <div class="card-header" style="background-color:{{$anken->color}};">
            <h5 class="card-title" style="text-align:center;">{{$anken->name}}</h5>
          </div>
          <div class="card-body">
            
            <?PHP
              $boderColor=$anken->color !="white"?$anken->color:"gray";
            ?>
            
            <div class="input-group" style="margin-top:10px;" >
                <span class="input-group-text sm-text" style=" border-color:{{$boderColor}};">コード</span>
                <span class="form-control sm-text" style=" border-color:{{$boderColor}};">{{$anken->code}}</span>
            </div>
            
            <div class="input-group" style="margin-top:10px;">
                <span class="input-group-text sm-text" style="width:63px; border-color:{{$boderColor}};">部署　</span>
                <span class="form-control sm-text" style=" border-color:{{$boderColor}};">{{$anken->depa_name}}</span>
            </div>
            
            <div class="input-group" style="margin-top:10px;">
                <span class="input-group-text sm-text" style=" border-color:{{$boderColor}};">単価　</span>
                <span class="form-control sm-text anken-{{$anken->id}} anken-tank" style=" border-color:{{$boderColor}};"></span>
            </div>
            
            <div class="input-group" style="margin-top:10px;">
                <span class="input-group-text sm-text" style="border-color:{{$boderColor}};">カラー</span>
                <span class="form-control sm-text" style="border-color:{{$boderColor}};">{{$anken->colorName}}</span>
            </div>
            
          </div>
        </div>
      </div>
  @endforeach
</div>

<br>
<nav aria-label="..."  style="width: 100px;">
  <ul class="pagination">
    <li class="page-item">
      {!! link_to_route('ankenCreate','top',[1,$code??'none',$depa],['class'=>'page-link']) !!}
    </li>
    @foreach($pageNumber as $num)
      <li class="page-item {{$num==$selectPage? 'active':''}}">{!! link_to_route('ankenCreate',$num,[$num,$code,$depa],['class'=>'page-link']) !!}<li>
    @endforeach
    <li class="page-item">
      {!! link_to_route('ankenCreate','end',[$pageCount,$code??'none',$depa],['class'=>'page-link']) !!}
    </li>
  </ul>
</nav>

@include("schedule.ankenCreate")
@include("schedule.ankenShow")

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
<script>
  
  //案件click
  let chratData="";
  async function ankenShow(anken){
    
    if(chratData!=''){
      await chratData.destroy();
    }
    
    document.querySelector('#modal2').click();
    document.querySelector('#ankenId').value=anken.id;
    document.querySelector('#ankenId2').value=anken.id;
    document.querySelector('#editCode').value=anken.code;
    document.querySelector('#editName').value=anken.name;
    document.querySelector('#editAddress').value=anken.address;
    document.querySelector('#editTank').innerHTML=document.querySelector(".anken-"+anken.id).innerHTML;
    document.querySelector('#editDay').innerHTML='';
    document.querySelector('#editColor').value=anken.color;
    document.querySelector('#editDepa').value=anken.depa_id;
    // document.querySelector('#editColorSpan').style.backgroundColor=anken.color;
    document.querySelector('.show-modal-header').style.backgroundColor=anken.color;
    document.querySelector('.anken-title').innerHTML=anken.name;
    document.querySelector("#editColorName").value=anken.colorName;
    document.querySelectorAll('.editForms').forEach(function(e){
      e.setAttribute('disabled',true);
    });
    document.querySelector(".editMode").classList.remove("d-none");
    document.querySelector(".editKaijo").classList.add("d-none");
    document.querySelector(".updateBtn").classList.add("d-none");
    
       ankenGet(anken.id).done(function(data, status, xhr) {
            
            
            
            if(!data[0]){
              document.querySelector('.userIndex').innerHTML='';
              return;
            }
            
            let labelArray=[];
            for(d of data){
              labelArray.push(d.name);
            }
            
            let tankArray=[];
            for(d of data){
              tankArray.push(d.ankenTanka);
            }
            
            const colorArray = [
                                "#CD1F1D",
                                "#A452A7",
                                "#e6b422",
                                "#647C32",
                                "#1899D3",//青
                                "#B3E6FF",
                                "#EB6A26",
                                "#ED87AD",//ピンク
                                "#C6B7A4",//ベージュ
                                "#E2DA56",
                                "#9B5F41",
                            ];
          
            //tableSet
            let forCount=0;
            let userHtml='';
            let dayCount=0;
            for(d of data){
            
              userHtml+=`
                        <tr>
                          <td style="background-color: ${colorArray[forCount]}; width: 10px;"></td>
                          <td style="overflow: hidden;max-width: 100px;" nowrap>${d.name}</td>
                          <td style="text-align: right;">${d.ankenTanka.toLocaleString()}</td>
                          <td style="text-align: right;">${d.oneDayTanka.toLocaleString()}</td>
                          <td style="text-align: right;">${d.ankenSu}</td>
                        </tr>`
              dayCount+=d.ankenSu;
              forCount++;
            }
            document.querySelector('#editDay').innerHTML=dayCount;
            
            document.querySelector('.userIndex').innerHTML=userHtml;
            
            var ctx = document.getElementById("myDoughnutChart");
            
            function chartSet(){
              var myDoughnutChart= new Chart(ctx, {
                type: 'doughnut',
                data: {
                  labels: labelArray, //データ項目のラベル
                  datasets: [{
                      backgroundColor: colorArray,
                      data: tankArray //グラフのデータ
                  }]
                },
                options: {
                 legend: {
                      display: false // 凡例を非表示にする
                  }
                }
              });

              chratData=myDoughnutChart;
            }
            setTimeout(chartSet,500);
      });
  }
  
  async function ankenTank(){
    await ankenGet('all').done(function(data, status, xhr) {
      
        document.querySelectorAll(".anken-tank").forEach(function(e){
          e.innerHTML='';
        });
    
        for(d of data){
          const el = document.querySelector(".anken-"+d.title_id);
          if(el){
            el.innerHTML=d.ankenTanka.toLocaleString();
          }
          //document.querySelector(".anken-"+d.title_id).innerHTML=d.ankensu;
        }
    });
    
  }
  
  
  window.onload = ankenTank();　
  
  
  
  
  
  function ankenGet(ankenId){
    return $.ajax({
        url: '/anken/index/get', 
          type: "get", 
          dataType: 'json',
          contentType: "application/x-www-form-urlencoded; charset=UTF-8",
          data:{
              ankenId: ankenId
          },
          processData: true,
        });
  }
  
  
  
  function ankenSearch(){
    const codeSearch = document.querySelector("#codeSearch").value;
    const depaSearch = document.querySelector("#depaSearch").value;
    
    document.querySelectorAll(".ankenCard").forEach(function(e){
      e.classList.remove("d-none");
      if(e.dataset.code.indexOf(codeSearch)==-1 && codeSearch!=''){
          e.classList.add("d-none");
      }
      if(e.dataset.depa!=depaSearch && depaSearch!="all"){
          e.classList.add("d-none");
      }
    });
  }
  
  
  
  //編集モード切替
  function ankenEdit(target){
    
    if(target.innerHTML=="編集"){
      document.querySelectorAll('.editForms').forEach(function(e){
        e.removeAttribute('disabled');
      });
      document.querySelector(".editKaijo").classList.remove("d-none");
      document.querySelector(".updateBtn").classList.remove("d-none");
      target.classList.add("d-none");
    
      
    }else{
      document.querySelectorAll('.editForms').forEach(function(e){
        e.setAttribute('disabled',true);
      })
      document.querySelector(".editMode").classList.remove("d-none");
      document.querySelector(".updateBtn").classList.add("d-none");
      target.classList.add("d-none");
    }
    
    
    
    
    
  }
  
  
 
  
  
  
  
  
</script>


@endsection