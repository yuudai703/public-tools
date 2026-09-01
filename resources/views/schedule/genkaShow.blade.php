<style>
   
</style>

<div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel2" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header position-relative show-modal-header">
        <h5 class="modal-title anken-title position-absolute top-50 start-50 translate-middle"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
        <div class="modal-body">
          {!! Form::open(['route'=>'ankenUpdate', 'method' => 'put','id'=>'main_form']) !!}
          <div class="container">
            <div class="row justify-content-md-center">
              <div class="col-md-5">
                  <div class="input-group inputText"style="margin-top:10px;">
                      <span class="input-group-text createOrEditSpan" id="basic-addon1">案件コード</span>
                      <input type="text" class="form-control editForms" id="editCode"  name="code" placeholder="code..." aria-label="code..." aria-describedby="basic-addon1" disabled>
                  </div>
              </div>
              <div class="col-md-5">
                  <div class="input-group inputText" style="margin-top:10px;">
                      <span class="input-group-text createOrEditSpan" id="basic-addon2">案件名　</span>
                      <input type="text" class="form-control editForms" id="editName" name="name" placeholder="name..." aria-label="name..." aria-describedby="basic-addon2" disabled>
                  </div>
              </div>
            </div>
            <div class="row justify-content-md-center">
              <div class="col-md-5">
                  <div class="input-group inputText" style="margin-top:10px;">
                      <span class="input-group-text editColorLabel createOrEditSpan" id="editColorSpan">カラー　　</span>
                      <select class="form-control editForms" onchange="colorSelectedEdit(this)" name="color" id="editColor" disabled>
                          <option class="editOption" value="white" style="background-color:white;"></option>  <!-- Vivid Yellow -->
                          <option class="editOption" value="#D9B3FF" style="background-color:#D9B3FF;">パステル紫</option>  <!-- Pastel Purple -->
                          <option class="editOption" value="#B3B3FF" style="background-color:#B3B3FF;">パステル青</option>  <!-- Pastel Blue -->
                          <option class="editOption" value="#C1CFFF" style="background-color:#C1CFFF;">パステルライトブルー</option>  <!-- Pastel Light Blue -->
                          <option class="editOption" value="#B3E6FF" style="background-color:#B3E6FF;">パステル水色</option>  <!-- Pastel Cyan -->
                          <option class="editOption" value="#B3FFD9" style="background-color:#B3FFD9;">パステルミントグリーン</option>  <!-- Pastel Mint Green -->
                          <option class="editOption" value="#B3FFB3" style="background-color:#B3FFB3;">パステルグリーン</option>  <!-- Pastel Green -->
                          <option class="editOption" value="#D1FFB3" style="background-color:#D1FFB3;">パステルライムグリーン</option>  <!-- Pastel Lime Green -->
                          <option class="editOption" value="#FFFFB3" style="background-color:#FFFFB3;">パステルイエロー</option>  <!-- Pastel Yellow -->
                          <option class="editOption" value="#FFD9B3" style="background-color:#FFD9B3;">パステルピンク</option>  <!-- Pastel Peach -->
                          <option class="editOption" value="#FFE6B3" style="background-color:#FFE6B3;">パステルライトオレンジ</option>  <!-- Pastel Light Orange -->
                          <option class="editOption" value="#FFB3A1" style="background-color:#FFB3A1;">パステルオレンジ</option>  <!-- Pastel Coral -->
                          <option class="editOption" value="#FFCCCC" style="background-color:#FFCCCC;">パステルライトレッド</option>  <!-- Pastel Light Red -->
                          <option class="editOption" value="#FFC300" style="background-color:#FFC300;">黄色</option>  <!-- Vivid Yellow -->
                          <option class="editOption" value="#DAF7A6" style="background-color:#DAF7A6;">ライトグリーン</option>  <!-- Vivid Light Green -->
                          <option class="editOption" value="#33FF57" style="background-color:#33FF57;">緑</option>  <!-- Vivid Green -->
                          <option class="editOption" value="#33FFBD" style="background-color:#33FFBD;">ターコイズ</option>  <!-- Vivid Mint Green -->
                          <option class="editOption" value="#33D4FF" style="background-color:#33D4FF;">色</option>  <!-- Vivid Cyan -->
                          <option class="editOption" value="#3388FF" style="background-color:#3388FF;">青</option>  <!-- Vivid Blue -->
                          <option class="editOption" value="#335CFF" style="background-color:#335CFF;">濃青</option>  <!-- Vivid Indigo -->
                          <option class="editOption" value="#8C33FF" style="background-color:#8C33FF;">紫</option>  <!-- Vivid Violet -->
                          <option class="editOption" value="#FF33E1" style="background-color:#FF33E1;">ピンク</option>  <!-- Vivid Magenta -->
                          <option class="editOption" value="#FF3385" style="background-color:#FF3385;">濃ピンク</option>  <!-- Vivid Pink -->
                          <option class="editOption" value="#FF5733" style="background-color:#FF5733;">オレンジ</option>  <!-- Vivid Red -->
                          <option class="editOption" value="#FF6F33" style="background-color:#FF6F33;">薄オレンジ</option>  <!-- Vivid Coral -->
                          <option class="editOption" value="#A4374B" style="background-color:#A4374B;">ダークレッド</option>
                          <option class="editOption" value="#C14476" style="background-color:#C14476;">ダークピンク</option>
                          <option class="editOption" value="#C6539D" style="background-color:#C6539D;">ダークマゼンタ</option>
                          <option class="editOption" value="#AD5EC9" style="background-color:#AD5EC9;">ダークバイオレット</option>
                          <option class="editOption" value="#6F5BC8" style="background-color:#6F5BC8;">ダークブルー</option>
                          <option class="editOption" value="#7589D1" style="background-color:#7589D1;">ダークライトブルー</option>
                          <option class="editOption" value="#71B5D0" style="background-color:#71B5D0;">ダークシアン</option>
                          <option class="editOption" value="#6ACDA1" style="background-color:#6ACDA1;">ダークミントグリーン</option>
                          <option class="editOption" value="#66CC74" style="background-color:#66CC74;">ダークグリーン</option>
                          <option class="editOption" value="#95C85B" style="background-color:#95C85B;">ダークライムグリーン</option>
                          <option class="editOption" value="#B9BB3E" style="background-color:#B9BB3E;">ダークイエロー</option>
                          <option class="editOption" value="#B48C3C" style="background-color:#B48C3C;">ダークピーチ</option>
                          <option class="editOption" value="#C9AB5E" style="background-color:#C9AB5E;">ダークライトオレンジ</option>
                          <option class="editOption" value="#995833" style="background-color:#995833;">ダークコーラル</option>
                          <option class="editOption" value="#734D26" style="background-color:#734D26;">ダークブラウン</option>
                      </select>
                  </div>
              </div>
              <div class="col-md-5">
                  <div class="input-group inputText" style="margin-top:10px;">
                      <span class="input-group-text colorLabel createOrEditSpan">部署　　</span>
                      <select class="form-control editForms" id="editDepa" name="depa" disabled>
                          @foreach($depas as $depa)
                              <option class="editDepa" value="{{$depa->id}}">{{$depa->name}}</option>
                          @endforeach
                      </select>
                  </div>
              </div>
            </div>
            <div class="row justify-content-md-center">
                  <div class="col-md-10">
                    <div class="input-group inputText" style="margin-top:10px;">
                        <span class="input-group-text createOrEditSpan" id="basic-addon6">住所　</span>
                         <input type="text" class="form-control editForms" id="editAddress" name="address" style="font-size:12px;" aria-describedby="basic-addon6" disabled>
                    </div>
                  </div>
            </div>
            <div class="row justify-content-md-center">
              <div class="col-md-5">
                  <div class="input-group inputText" style="margin-top:10px;">
                      <span class="input-group-text createOrEditSpan" id="basic-addon4">単価￥　</span>
                      <span class="form-control en editForms"style="background-color: gainsboro;" name="tank" aria-describedby="basic-addon4" id='editTank' disabled></span>
                  </div>
              </div>
              
              <br>
              <br>
              <div class="col-md-5">
                  <div class="input-group inputText" style="margin-top:10px;">
                      <span class="input-group-text createOrEditSpan" id="basic-addon5">日数　</span>
                      <span class="form-control en editForms" aria-describedby="basic-addon5" style="background-color: gainsboro;" id='editDay' disabled></span>
                  </div>
              </div>
            </div>
            
            
            
            
            
            
            <br>
              <div class="row justify-content-md-center">
                  <div class="col-md-10">
                      <button class='btn btn-outline-secondary updateBtn d-none' style="width:100%; margin-bottom:30px;">更新
                      </button>
                      <input type="hidden" name="ankenId" id="ankenId2">
                      <input type='hidden' id="editColorName" name="colorName">
                  </div>
              </div>
          </div>
        {!! Form::close()!!}
        
        
        <div class="showBottomContents">
        <!--<div class="row row-cols-1 row-cols-md-2 g-2">-->
        <div class="canvasCart" style="">
          <p style="position :absolute; right:268px; top: 140px;">単価割合</p>
          <canvas id="myDoughnutChart" ></canvas>
          <div class=" position-table">
           <table class="table" style="overflow-y:auto; font-size: 12px;">
              <tr  style="position: sticky; top: 0px; z-index: 3;">
                <td style="background-color:#DDDDDD!important; text-align: center; max-width: 10px;" nowrap style="height:20px;"></td>
                <td style="background-color:#DDDDDD!important; text-align: center; max-width: 120px;" nowrap style="height:20px;">名前</td>
                <td style="background-color:#DDDDDD!important; text-align: center;">合計<br>単価</td>
                <td style="background-color:#DDDDDD!important; text-align: center;" nowrap>日額</td>
                <td style="background-color:#DDDDDD!important; text-align: center;" nowrap>日数</td>
              </tr>
              <tbody class="userIndex">
              </tbody>
            </table>
          </div>
        </div>
        </div>
          
            

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success bi bi-tools editMode" onClick="ankenEdit(this)">編集</button>
                <button type="button" class="btn btn-success bi bi-tools editKaijo d-none" onClick="ankenEdit(this)">編集解除</button>
            {!! Form::open(['route'=>'ankenDelete', 'method' => 'delete',"onsubmit"=>"return beforeSubmit()"]) !!}
                <button type="submit" class="btn btn-danger">削除</button>
                <input type="hidden" name="ankenId" id="ankenId">
            {!! Form::close()!!}
      </div>
    </div>
  </div>
</div>


<script>
//色
    function colorSelectedEdit(e){
        document.querySelector(".show-modal-header").style.backgroundColor=e.value;
        document.querySelectorAll(".editOption").forEach(function(e){
            if(e.selected == true){
                document.querySelector("#editColorName").value=e.innerHTML;
            }
        });
    }
    
    function beforeSubmit() {
      if(window.confirm('削除してよろしいでしょうか？')) {
        return true;
      } else {
        return false;
      }
    }
</script>
