<!-- Modal toggle -->
<button data-modal-target="kansetu-modal" data-modal-toggle="kansetu-modal" class="shadow-md shadow-cyan-950 btnStyle mr-2 mt-1 mb-4 float-right block text-white  focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-1 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
    間接費
</button>
  
  <!-- Main modal -->
  <div id="kansetu-modal" data-modal-backdrop="kansetu" tabindex="-1" aria-hidden="true" class="backdrop-blur-[2px] hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
      <div class="relative p-4 w-full max-w-4xl max-h-full">
          <!-- Modal content -->
          <div style="background-color: #ffffffad;" class="relative bg-white rounded-lg shadow dark:bg-gray-700">
              <!-- Modal header -->
              <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                  <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                      間接費追加
                  </h3>
                  <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="kansetu-modal">
                      <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                      </svg>
                      <span class="sr-only">Close modal</span>
                  </button>
              </div>
              <!-- Modal body -->
              <form action="{{route('mitumoriSetubiKai.storeKansetu')}}" method="POST"  enctype="multipart/form-data">
                @csrf
                <input type="hidden" value="{{ $mitumori->id }}" name="mitumoriId">
                <div class="p-4 md:p-5 space-y-4">
                    <div class="flex">
                        <span class="w-32 text-center py-3  px-3 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 border-e-0 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                            項目選択
                        </span>
                        
                        {{-- <input required list="koziDatas" type="name" name="name" id="website-admin" class="rounded-none rounded-e-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"> --}}
                        <select id="kansetuDatas" onchange="komokuChange(this.value)" name="kansetu_code" class="rounded-none rounded-e-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            @forEach ($kansetus as $kansetu)
                            <option value="{{ $kansetu->code }}" @if($kansetu->code==$selectedCode) selected @endif>{{ $kansetu->name }}</option>
                            @endforeach
                        </select>
                    </div> 
                    <table class="table-auto w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        {{-- @if($kansetuKoumokus->where("selectedKansetu",1)->first()) --}}
                            @foreach ($kansetuKoumokus as $komoku)
                                <tr>
                                    <td nowrap class="@if($komoku->bun_code!=$selectedCode) hidden @endif komoku border border-gray-400" data-komoku="{{ $komoku->bun_code }}">{!! $komoku->name !!}</td>
                                    <td nowrap class="@if($komoku->bun_code!=$selectedCode) hidden @endif komoku border border-gray-400" data-komoku="{{ $komoku->bun_code }}">
                                        {!! nl2br($komoku->j_exp) !!}
                                        @if($komoku->bun_code==510 || in_array($komoku->code,[5,6,7]))
                                            <input name="{{'kansetu_rate_'.$komoku->bun_code.'_'.$komoku->code}}" class="p-0 pr-2 text-right w-20 text-gray-900 text-sm border-gray-300" type="text" value="{{ $komoku->rate }}">
                                            @if($komoku->code==7) 円 @endif    
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        {{-- @else
                            @foreach ($kansetuKoumokus as $komoku)
                                <tr>
                                    <td nowrap class="@if($komoku->bun_code<>510) hidden @endif komoku" data-komoku="{{ $komoku->bun_code }}">{{ $komoku->name }}={{ $komoku->j_exp }}<input name="{{'kansetu_rate_'.$komoku->bun_code.'_'.$komoku->code}}" class="p-0 pr-2 text-right w-20 text-gray-900 text-sm border-gray-300" type="text" value="{{ $komoku->rate }}"></td>
                                </tr>
                            @endforeach
                        @endif --}}
                    </table>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button data-modal-hide="kansetu-modal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">閉じる</button>
                    <button type="submit" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">登録</button>
                </div>
            </div>
            </form>
          </div>
      </div>

      <script>

        function komokuChange(v){

            document.querySelectorAll('.komoku').forEach(element => {
                if(element.dataset.komoku == v ){
                    element.classList.remove('hidden');
                }else{
                    element.classList.add('hidden');
                }
            });
        }
      </script>