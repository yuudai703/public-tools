<!-- Modal -->
<div class="modal fade" id="outUserEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="">
  <div class="modal-dialog modal-sm"style="pointer-events: none;">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="outUserEditModalLabel">社外メンバー編集</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" ></button>
      </div>
      {!! Form::open(['route' => 'scheduleIndex','method'=>'post','id'=>"editOutUser"]) !!}
      <div class="modal-body">
        <div class="input-group inputText" style="margin-top:10px;">
            <span class="input-group-text" style="width:60px;">表示名</span>
            <input type="text" list="outUserName" id='editOutUserName' class="eventsForms form-control" name="outUserName" placeholder="" aria-describedby="basic-addon1">
            <datalist id='outUserName'>
                <option data-color="white" class="anken-"  data-address="" value="">未選択</option>
                @if($ankens)
                @forEach($ankens as $anken)
                    <option style="background-color:{{$anken->color}};" data-address="{{$anken->address}}" data-color="{{$anken->color}}" class="{{'anken-'.$anken->id}}" value="{{$anken->name}}" >{{$anken->name}}</option>
                @endforeach
                @endif
            </datalist>
        </div> 
         
        <div class="input-group inputText" style="margin-top:10px;">
        <span class="input-group-text" style="width:60px;">表示部署</span>
        <select name="outUserDepa" class='form-control depaSelect eventsForms' id="editOutUserDepa">
            @forEach($depas as $dapa)
                <option value="{{$dapa->id}}" @if($selectdepa==$dapa->id) selected @endif >{{$dapa->name}}</option>
            @endforeach
        </select>
        </div>
        <input type="hidden" value="{{$ym}}" name="target_ym">
        <input type="hidden" id="outUserEditId" name="outUserEditId">
        {!! Form::close() !!}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary"  data-bs-dismiss="modal">Close</button>
        <button form="editOutUser" class="btn btn-secondary"value="1" name="deleteOutUser">削除</button>
        <button form="editOutUser" class="btn btn-secondary" value="1" name="updateOutUser">更新</button>
      </div>
    </div>
  </div>
</div>