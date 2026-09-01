


<style>
     .flexCenter{
         display:flex; 
         justify-content: center;
         align-items: center;
         width: 180px;
     }
     
     .inputText{
         width: 300px;
     }
     
     .en{
         text-align:right;
     }
     
     option:hover{
         pointer-events: none!important;
     }
     
     option:hover {
            background-color: initial!important;
            border-color: initial!important;
            color: initial!important;
        }
        
    
    
</style>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header create-modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">


<h2 style="text-align: center; margin-top: 20px; margin-bottom: 20px;">案件登録</h2>


{!! Form::open(['route'=>'ankenStore', 'method' => 'post','id'=>'main_form']) !!}
<div class="container">
  <div class="row justify-content-md-center">
    <div class="col-md-5">
        <div class="input-group inputText" style="margin-top:10px;">
            <span class="input-group-text createOrEditSpan createColorBorder" id="basic-addon1">案件コード</span>
            <input type="text" class="form-control createColorBorder"  name="code" placeholder="code..." aria-label="code..." aria-describedby="basic-addon1" required>
        </div>
    </div>
    <div class="col-md-5">
        <div class="input-group inputText" style="margin-top:10px;">
            <span class="input-group-text createOrEditSpan createColorBorder" id="basic-addon2">案件名　</span>
            <input list="eventsOp" class="form-control createColorBorder" name="name" placeholder="title..." aria-label="name..." aria-describedby="basic-addon2" required>
            <datalist id="eventsOp">
              @forEach($events as $event)
               <option value="{{$event->title}}"></option>
              @endforEach
            </datalist>
        </div>
    </div>
  </div>
  <div class="row justify-content-md-center">
    <div class="col-md-5">
        <div class="input-group inputText" style="margin-top:10px;">
            <span class="input-group-text colorLabel createOrEditSpan createColorBorder">カラー　　</span>
            <select class="form-control createColorBorder" onchange="colorSelected(this)" name="color">
                <option class="createOption" value="white" style="background-color:white;"></option>  <!-- Vivid Yellow -->
                <option class="createOption" value="#D9B3FF" style="background-color:#D9B3FF;">薄紫</option>  <!-- Pastel Purple -->
                <option class="createOption" value="#B3B3FF" style="background-color:#B3B3FF;">薄青</option>  <!-- Pastel Blue -->
                <option class="createOption" value="#C1CFFF" style="background-color:#C1CFFF;">薄ライトブルー</option>  <!-- Pastel Light Blue -->
                <option class="createOption" value="#B3E6FF" style="background-color:#B3E6FF;">薄水色</option>  <!-- Pastel Cyan -->
                <option class="createOption" value="#B3FFD9" style="background-color:#B3FFD9;">薄ミントグリーン</option>  <!-- Pastel Mint Green -->
                <option class="createOption" value="#B3FFB3" style="background-color:#B3FFB3;">薄グリーン</option>  <!-- Pastel Green -->
                <option class="createOption" value="#D1FFB3" style="background-color:#D1FFB3;">薄ライムグリーン</option>  <!-- Pastel Lime Green -->
                <option class="createOption" value="#FFFFB3" style="background-color:#FFFFB3;">薄イエロー</option>  <!-- Pastel Yellow -->
                <option class="createOption" value="#FFD9B3" style="background-color:#FFD9B3;">薄ピンク</option>  <!-- Pastel Peach -->
                <option class="createOption" value="#FFE6B3" style="background-color:#FFE6B3;">薄ライトオレンジ</option>  <!-- Pastel Light Orange -->
                <option class="createOption" value="#FFB3A1" style="background-color:#FFB3A1;">薄オレンジ</option>  <!-- Pastel Coral -->
                <option class="createOption" value="#FFCCCC" style="background-color:#FFCCCC;">薄レッド</option>  <!-- Pastel Light Red -->
                <option class="createOption" value="#FFC300" style="background-color:#FFC300;">黄色</option>  <!-- Vivid Yellow -->
                <option class="createOption" value="#DAF7A6" style="background-color:#DAF7A6;">ライトグリーン</option>  <!-- Vivid Light Green -->
                <option class="createOption" value="#33FF57" style="background-color:#33FF57;">緑</option>  <!-- Vivid Green -->
                <option class="createOption" value="#33FFBD" style="background-color:#33FFBD;">ターコイズ</option>  <!-- Vivid Mint Green -->
                <option class="createOption" value="#33D4FF" style="background-color:#33D4FF;">水色</option>  <!-- Vivid Cyan -->
                <option class="createOption" value="#3388FF" style="background-color:#3388FF;">青</option>  <!-- Vivid Blue -->
                <option class="createOption" value="#335CFF" style="background-color:#335CFF;">濃青</option>  <!-- Vivid Indigo -->
                <option class="createOption" value="#8C33FF" style="background-color:#8C33FF;">紫</option>  <!-- Vivid Violet -->
                <option class="createOption" value="#FF33E1" style="background-color:#FF33E1;">ピンク</option>  <!-- Vivid Magenta -->
                <option class="createOption" value="#FF3385" style="background-color:#FF3385;">濃ピンク</option>  <!-- Vivid Pink -->
                <option class="createOption" value="#FF5733" style="background-color:#FF5733;">オレンジ</option>  <!-- Vivid Red -->
                <option class="createOption" value="#FF6F33" style="background-color:#FF6F33;">薄オレンジ</option>  <!-- Vivid Coral -->
                <option class="createOption" value="#A4374B" style="background-color:#A4374B;">ダークレッド</option>
                <option class="createOption" value="#C14476" style="background-color:#C14476;">ダークピンク</option>
                <option class="createOption" value="#C6539D" style="background-color:#C6539D;">ダークマゼンタ</option>
                <option class="createOption" value="#AD5EC9" style="background-color:#AD5EC9;">ダークバイオレット</option>
                <option class="createOption" value="#6F5BC8" style="background-color:#6F5BC8;">ダークブルー</option>
                <option class="createOption" value="#7589D1" style="background-color:#7589D1;">ダークライトブルー</option>
                <option class="createOption" value="#71B5D0" style="background-color:#71B5D0;">ダークシアン</option>
                <option class="createOption" value="#6ACDA1" style="background-color:#6ACDA1;">ダークミントグリーン</option>
                <option class="createOption" value="#66CC74" style="background-color:#66CC74;">ダークグリーン</option>
                <option class="createOption" value="#95C85B" style="background-color:#95C85B;">ダークライムグリーン</option>
                <option class="createOption" value="#B9BB3E" style="background-color:#B9BB3E;">ダークイエロー</option>
                <option class="createOption" value="#B48C3C" style="background-color:#B48C3C;">ダークピーチ</option>
                <option class="createOption" value="#C9AB5E" style="background-color:#C9AB5E;">ダークライトオレンジ</option>
                <option class="createOption" value="#995833" style="background-color:#995833;">ダークコーラル</option>
                <option class="createOption" value="#734D26" style="background-color:#734D26;">ダークブラウン</option>
            </select>
        </div>
    </div>
    <!--<div class="col-md-5">-->
    <!--    <div class="input-group inputText" style="margin-top:10px;">-->
    <!--        <span class="input-group-text createOrEditSpan createColorBorder" id="basic-addon4">単価￥　</span>-->
    <!--        <input type="text" class="form-control en createColorBorder"  name="tank" aria-describedby="basic-addon4">-->
    <!--    </div>-->
    <!--</div>-->
        <div class="col-md-5">
            <div class="input-group inputText" style="margin-top:10px;">
                <span class="input-group-text colorLabel createOrEditSpan createColorBorder">部署　　</span>
                <select class="form-control createColorBorder" name="depa">
                    @foreach($depas as $depa)
                        <option value="{{$depa->id}}">{{$depa->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    
    
    
  </div>
  <div class="row justify-content-md-center">
    <div class="col-md-10">
        <div class="input-group inputText" style="margin-top:10px;">
            <span class="input-group-text createOrEditSpan" id="basic-addon5">　住所</span>
            <input type="text" class="form-control createColorBorder" style="font-size:11px;"  name="address"  aria-describedby="basic-addon5" required>
        </div>
    </div>
  </div>
  <div class="row justify-content-md-center">
    
    <br>
    <br>
    <div class="col-md-5">
        <div class="input-group inputText">
            
        </div>
    </div>
  </div>
  <br>
    <div class="row justify-content-md-center">
        <div class="col-md-5">
            <button class='btn  btn-outline-secondary' style="width:100%;">登録
            </button>
        </div>
    </div>
</div>
<input type='hidden' id="colorName" name="colorName">
{!! Form::close()!!}

</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <!--<button type="button" class="btn btn-primary">Send message</button>-->
      </div>
    </div>
  </div>
</div>

<script>
    function colorSelected(e){
        document.querySelector(".create-modal-header").style.backgroundColor=e.value;
        // e.style.backgroundColor=e.value;        
        // document.querySelectorAll(".createColorBorder").forEach(function(e){
        // console.log(e);
        //     e.style.borderColor=e.value;
        // });
        
        document.querySelectorAll(".createOption").forEach(function(e){
            if(e.selected == true){
                document.querySelector("#colorName").value=e.innerHTML;
                console.log(e);
            }
        });
    }
</script>

