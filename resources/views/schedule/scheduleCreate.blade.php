<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="" onclick='maskClick()'>
  <div class="modal-dialog"style="pointer-events: none;">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">案件追加</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick='selectedRelease()'></button>
      </div>
      <div class="modal-body">
            <!--<div class="input-group inputText"style="margin-top:10px;">-->
                <!--<span class="input-group-text createOrEditSpan" id="basic-addon1" style="width:90px;">案件選択</span>-->
                <!--<input type="text" class="form-control editForms" id="editCode"  name="code" placeholder="code..." aria-label="code..." aria-describedby="basic-addon1" disabled>-->
            
                <!--<input  id='anken' class="form-control pasteTarget textContent eventsForms">-->
                
            <!--</div>-->
             <div class="input-group inputText" style="margin-top:10px;">
                <span class="input-group-text" style="width:90px;">案件名</span>
                <input list="ankenlist" type="text" class="form-control anken_nm pasteTarget textContent eventsForms" value="" style="font-size:13px;" onchange="addressInput(this.value)" name="anken_nm" placeholder="案件選択にない場合、入力" aria-describedby="basic-addon1">
                <datalist id='ankenlist'>
                    <option data-color="white" class="anken-"  data-address="" value="">未選択</option>
                    @if($ankens)
                    @forEach($ankens as $anken)
                        <option style="background-color:{{$anken->color}};" data-address="{{$anken->address}}" data-color="{{$anken->color}}" class="{{'anken-'.$anken->id}}" value="{{$anken->name}}" >{{$anken->name}}</option>
                    @endforeach
                    @endif
                </datalist>
                
                <select class="form-control selectColor pasteTarget" name="color" style="max-width:80px;">
                    <option class="colorOption" value="white" style="text-align:center;background-color:white;">白</option>  <!-- Pastel Light Red -->
                    <option class="colorOption" value="#D9B3FF" style="text-align:center;background-color:#D9B3FF;">紫</option>  <!-- Pastel Purple -->
                    <option class="colorOption" value="#B3B3FF" style="text-align:center;background-color:#B3B3FF;">ﾈｲﾋﾞｰ</option>  <!-- Pastel Blue -->
                    <option class="colorOption" value="#7fbfff" style="text-align:center;background-color:#7fbfff">青</option>  <!-- Pastel Blue -->
                    <option class="colorOption" value="#7fffff" style="text-align:center;background-color:#7fffff;">水色</option>  <!-- Pastel Blue -->
                    <option class="colorOption" value="#7fff7f" style="text-align:center;background-color:#7fff7f;">緑</option>  <!-- Pastel Green -->
                    <option class="colorOption" value="#bfff7f" style="text-align:center;background-color:#bfff7f;">黄緑</option>  <!-- Pastel Green -->
                    <option class="colorOption" value="#B3FFB3" style="text-align:center;background-color:#B3FFB3;">薄緑</option>  <!-- Pastel Green -->
                    <option class="colorOption" value="#FFFFB3" style="text-align:center;background-color:#FFFFB3;">黄</option>  <!-- Pastel Yellow -->
                    <option class="colorOption" value="#ffbf7f" style="text-align:center;background-color:#ffbf7f;">橙</option>  <!-- Pastel Yellow -->
                    <option class="colorOption" value="#ff7f7f" style="text-align:center;background-color:#ff7f7f;">赤</option>  <!-- Pastel Yellow -->
                    <option class="colorOption" value="#ff7fbf" style="text-align:center;background-color:#ff7fbf;">濃ﾋﾟﾝｸ</option>  <!-- Pastel Yellow -->
                    <option class="colorOption" value="#ff7fff" style="text-align:center;background-color:#ff7fff;">ﾋﾟﾝｸ</option>  <!-- Pastel Yellow -->
                    <option class="colorOption" value="#FFCCCC" style="text-align:center;background-color:#FFCCCC;">薄ﾋﾟﾝｸ</option>  <!-- Pastel Light Red -->
                    <option class="colorOption" value="saddlebrown" style="text-align:center;background-color:saddlebrown; color:white;">茶色</option>  <!-- Pastel Light Red -->
                    <option class="colorOption" value="darkred" style="text-align:center;background-color:darkred; color:white;">ﾀﾞｰｸﾚｯﾄﾞ</option>  <!-- Pastel Light Red -->
                    <option class="colorOption" value="darkgreen" style="text-align:center;background-color:darkgreen; color:white;">ﾀﾞｰｸｸﾞﾘｰﾝ</option>  <!-- Pastel Light Red -->
                    <option class="colorOption" value="darkblue" style="text-align:center;background-color:darkblue; color:white;">ﾀﾞｰｸﾌﾞﾙｰ</option>  <!-- Pastel Light Red -->
                    <option class="colorOption" value="black" style="text-align:center;background-color:black; color:white;">黒</option>  <!-- Pastel Light Red -->
                    <option class="colorOption" value="" style="text-align:center;">透明</option>  <!-- Pastel Light Red -->
                </select>
            </div>
            <div class="input-group inputText" style="margin-top:10px;">
                <span class="input-group-text" style="width:90px;">住所入力</span>
                <input type="text" class=" eventsForms form-control pasteTarget textContent address" value=""  name="address" placeholder="" aria-describedby="basic-addon1">
            </div>
        <br>
        <textarea id="eventContents" style="width: 100%; min-height: 120px;" class="form-control pasteTarget textContent" placeholder="作業内容"></textarea>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id='closeBtn' data-bs-dismiss="modal" onclick='selectedRelease()'>Close</button>
        <button type="button" class="btn" style="background-color:#99CC99; color:white;" id='pasteBtn' onclick='pasteEvent()' onclick='selectedRelease()'>Paste</button>
        <button type="button" class="btn btn-primary" id='submitBtn'>登録</button>
      </div>
    </div>
  </div>
</div>