<!-- Modal -->
<div class="modal fade" id="fullUpModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="">
  <div class="modal-dialog"style="pointer-events: none;">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">案件名一括変更</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="input-group inputText" style="margin-top:10px;">
                <span class="input-group-text" style="width:90px;">案件名</span>
                <input list="ankenlist" id="fullUpText" type="text" class="form-control anken_nm pasteTarget textContent eventsForms" style="font-size:13px;" name="anken_nm" placeholder="案件選択にない場合、入力" aria-describedby="basic-addon1">
                <datalist id='ankenlist'>
                    <option data-color="white" class="anken-"  data-address="" value="">未選択</option>
                    @if($ankens)
                    @forEach($ankens as $anken)
                        <option style="background-color:{{$anken->color}};" data-address="{{$anken->address}}" data-color="{{$anken->color}}" class="{{'anken-'.$anken->id}}" value="{{$anken->name}}" >{{$anken->name}}</option>
                    @endforeach
                    @endif
                </datalist><br>
        </div>
        <p style="font-size:10; color:red; margin:10px 0px 0px 0px;">※当月の同じ案件名を一括で変更します。</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary"data-bs-dismiss="modal" id='fullUpBtn' onClick="fullUpTitle()">一括更新</button>
      </div>
    </div>
  </div>
</div>