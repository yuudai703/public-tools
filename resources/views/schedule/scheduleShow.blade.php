<!-- Modal -->
<style>
#map {
  width: 100%;
  height: 600px;
  overflow: hidden;
}
#map iframe {
  width: 100%;
  height: 960px;
  margin-top: -170px;
}
</style>

<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="" onclick='maskClick2()'>
  <div class="modal-dialog"style="pointer-events: none;">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5 model-contents" id="eventModalLabel">Modal title</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick='edetClose()'></button>
      </div>
      <div class="modal-body">
        <div class="mainContents">
          <p id="showContents" class="model-contents" style="width:100%; max-height:150px; background-color: whitesmoke; padding: 10px; overflow: auto;"></p>
          <input type="hidden" class="model-contents"/>
          <input type="hidden" class="model-contents"/>
          <iframe class="showMap" src="" width="100%" height="200px" style="border:0;"  loading="lazy"></iframe>
        </div>  
          <div class="editContents d-none">
             <div class="input-group inputText" style="margin-top:10px;">
                <div class="input-group inputText" style="margin-top:10px;">
                <span class="input-group-text" style="width:90px;">案件名</span>
                <input list="ankenlist2" type="text" class="edit-contents form-control anken_nm textContent eventsForms" value="" style="font-size:13px;" onchange="addressInput(this.value)" name="anken_nm" placeholder="案件選択にない場合、入力" aria-describedby="basic-addon1">
                <datalist id='ankenlist2'>
                    <option data-color="white" class="anken-"  data-address="" value="">未選択</option>
                    @if($ankens)
                    @forEach($ankens as $anken)
                        <option style="background-color:{{$anken->color}};" data-address="{{$anken->address}}" data-color="{{$anken->color}}" class="{{'anken-'.$anken->id}}" value="{{$anken->name}}" >{{$anken->name}}</option>
                    @endforeach
                    @endif
                </datalist>
                
                <select class="form-control selectColor edit-contents" name="editColor" style="max-width:80px;padding:0px!important;">
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
                    <option class="colorOption" value="darkblue" style="text-align:center;background-color:darkblue; color:white; padding:0px!important;">ﾀﾞｰｸﾌﾞﾙｰ</option>  <!-- Pastel Light Red -->
                    <option class="colorOption" value="black" style="text-align:center;background-color:black; color:white;">黒</option>  <!-- Pastel Light Red -->
                    <option class="colorOption" value="" style="text-align:center;">透明</option>  <!-- Pastel Light Red -->
                </select>
            </div>
            </div>
            <div class="input-group inputText" style="margin-top:10px;">
                <span class="input-group-text" style="width:90px;">住所入力</span>
                <input type="text" class="edit-contents form-control pasteTarget textContent address eventsForms" value=""  name="address" placeholder="" aria-describedby="basic-addon1">
            </div>
        <br>
        <textarea id="eventContents" style="width: 100%; min-height: 120px;" class="edit-contents form-control pasteTarget textContent" placeholder="作業内容"></textarea>
        <input type="hidden" id="event-id"/>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary mainContents" id='closeBtn2' data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn editBtn" style="background-color:#99CC99; color:white;" onclick="editFormOpen()">編集</button>
        <button type="button" class="btn d-none editContents" style="background-color:#99CC99; color:white;" data-bs-dismiss="modal" onclick="editUpdate()">更新</button>
        <button type="button" class="btn mainContents" style="background-color:#006b8c; color:white;" data-bs-dismiss="modal" onclick="modelCopyHaveing()" onclick='selectedRelease()'>Copy</button>
        <button type="button" class="btn btn-danger mainContents" id='delteBtn'>削除</button>
      </div>
    </div>
  </div>
</div>
</div>