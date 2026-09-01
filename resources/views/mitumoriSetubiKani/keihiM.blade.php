<!-- Main modal -->
  <div id="keihi-modal" tabindex="-1" aria-hidden="true" class="backdrop-blur-[2px] hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
      <div class="relative p-4 w-[500px] max-h-full">
          <!-- Modal content -->
          <div style="background-color: #ffffffad;" class="relative bg-gray-100 rounded-lg shadow dark:bg-gray-700">
              <!-- Modal header -->
              <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                  {{-- <h3 id="sizaiH" class="text-xl font-semibold text-gray-900 dark:text-white">
                      その他、追加
                  </h3> --}}
                <p class="text-sm">
                    ※消耗品雑材の下に記載されます。<br/>
                    ※消耗品雑材の算出に含まれません。<br/>
                    ※車両関係や一式物を追加してください。
                </p>

                  <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="keihi-modal">
                      <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                      </svg>
                      <span class="sr-only">Close modal</span>
                  </button>
              </div>

                <div class="grid grid-flow-col gap-0">
                <!-- Modal body -->
                    <form id="keihi-store" action="{{route('mitumoriSetubiKani.addKeihi',['id'=>$mitumori->id])}}" method="post">
                        @csrf
                        <div class="flex mb-6 w-full px-2 pt-2">
                        <div class="w-[100%]">
                            {{-- <div class="flex">
                                <div class="flex items-center mb-4 ml-2">
                                    <input id="default-checkbox" type="checkbox" name="bottomLine" value="1" class="w-4 h-4 border border-default-medium rounded-xs bg-neutral-secondary-medium focus:ring-2 focus:ring-brand-soft">
                                    <label for="default-checkbox" class="select-none ms-2 text-sm font-medium text-heading">間接費より下に追加</label>
                                </div>
                            </div> --}}
                            <div class="flex">
                                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 border-e-0 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 16H5a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v1M9 12H4m8 8V9h8v11h-8Zm0 0H9m8-4a1 1 0 1 0-2 0 1 1 0 0 0 2 0Z"/>
                                    </svg>
                                </span>
                                <select onchange="keihiChange()" name="keihi" id="keihiSelectBox" type="text" class="font-black rounded-none rounded-e-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="">
                                    <option  class="keihiList" value="">登録後に手入力</option>
                                    @foreach ($keihis as $item)
                                        <option class="keihiList" value="{{ $item->id }}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>

                <table class="table-auto w-full text-sm text-left text-gray-800 font-black dark:text-gray-400">
                    <thead>
                        <tr>
                            <th class="text-center">仕様</th>
                            <th class="text-center">単位</th>
                            <th class="text-center">単価</th>
                        </tr>
                    </thead>
                    <tbody id="keihiTableBody">

                    </tbody>
                </table>



                
                <!-- Modal footer -->
                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button data-modal-hide="keihi-modal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">閉じる</button>
                    <button form="keihi-store" type="submit" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">登録</button>
                </div>
            </div>
          </div>
      </div>


      <script>


        const keihis = JSON.parse('<?php echo json_encode($keihis)?>');
        const keihiTable = document.querySelector("#keihiTableBody");
        function keihiChange(){
            let selectedKeihi=null;
            document.querySelectorAll(".keihiList").forEach((e)=>{
                if(e.selected) selectedKeihi = e;
            });

            for(keihi of keihis){
                if(keihi.id == selectedKeihi.value){
                    keihiTable.innerHTML=`
                    <tr>
                        <td class="text-center">`+ keihi.siyo +`</td>
                        <td class="text-center">`+ keihi.tani +`</td>
                        <td class="text-center">`+ keihi.tanka +`</td>
                    </tr>
                    `;
                    break;
                }
            }
            if(selectedKeihi.value=='')keihiTable.innerHTML=``;
        }


      </script>
  