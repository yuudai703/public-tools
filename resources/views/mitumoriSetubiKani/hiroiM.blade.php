
  
  <!-- Main modal -->
  <div id="hiroi-modal"  tabindex="-1" aria-hidden="true" class="backdrop-blur-[2px] hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
      <div class="relative p-4 max-w-3x1 max-h-full">
          <!-- Modal content -->
          <div style="background-color: #ffffffad;" class="relative bg-gray-100 rounded-lg shadow dark:bg-gray-700">
              <!-- Modal header -->
              <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">

                    <div class="relative bg-white w-24 rounded-xl flex items-center  border border-default bg-neutral-primary-soft rounded-base">
                        <input checked id="bordered-radio-1" type="radio" value="" name="bordered-radio" class="left-3 absolute w-4 h-4 text-neutral-primary border-default-medium bg-neutral-secondary-medium rounded-full checked:border-brand focus:ring-2 focus:outline-none focus:ring-brand-subtle border border-default appearance-none">
                        <label for="bordered-radio-1" class="w-full py-4 select-none text-sm font-medium text-heading">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;拾う</label>
                    </div>
                    &nbsp;
                    <div class="relative bg-white w-24 rounded-xl flex items-center  border border-default bg-neutral-primary-soft rounded-base">
                        <input id="bordered-radio-2" type="radio" value="" name="bordered-radio" class="left-2 absolute w-4 h-4 text-neutral-primary border-default-medium bg-neutral-secondary-medium rounded-full checked:border-brand focus:ring-2 focus:outline-none focus:ring-brand-subtle border border-default appearance-none">
                        <label for="bordered-radio-2" class="w-full py-4 select-none text-sm font-medium text-heading">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;歩掛編集</label>
                    </div>

                  <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="hiroi-modal">
                      <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                      </svg>
                      <span class="sr-only">Close modal</span>
                  </button>
              </div>

              <div class="grid grid-flow-col gap-0 h-[60vh]">
              <!-- Modal body -->
                <div class="col-span-4">
                     <div class="jstree col-span-1 h-[60vh] border min-w-[280px] ml-1  border-gray-400 bg-white rounded-xl" style="overflow-y: scroll; overflow-x: hidden!important;">
                        <h1>資材</h1>
                        <div id="ajax" class="demo"></div>
                    </div>
                </div>
                <div class="col-span-4">
                    <div class="flex">
                        <span class="w-[90px] text-center inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 border-e-0 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                            施工区分  
                        </span>
                        <select id="sekoSyu" name="sekoSyu"style=' text-align:center;'  form="hiroi-store" class=" w-[200px] bg-gray-50  placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded pl-1 py-1 transition duration-300 ease focus:outline-none focus:border-slate-600 hover:border-slate-600 shadow-sm focus:shadow-md appearance-none cursor-pointer">
                            <option value="sin">新設</option>
                            <option value="teSai">撤去(再利用)</option>
                            <option value="sai">再取付</option>
                            <option value="te">撤去</option>
                        </select>
                        <button onclick="addKikaku()" id="addKikakuBtn" class="float-right px-4 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">見積内規格追加</button>
                    </div>


                    <div class="hidden">
                                <span class="w-[90px] text-center inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 border-e-0 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                    作業員
                                </span>
                                <select id="sagyoSyu" name="sagyoSyu"style='text-align:center; '  form="hiroi-store" class="w-[200px] bg-gray-50 placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded pl-1 py-1 transition duration-300 ease focus:outline-none focus:border-slate-600 hover:border-slate-600 shadow-sm focus:shadow-md appearance-none cursor-pointer">
                                    <option value="den">電工</option>
                                    <option value="hutu">普通作業員</option>
                                    <option value="toku">特殊作業員</option>
                                </select>
                    </div>
                        <div id="modalBody" style="overflow-y:auto; max-height:51vh!important; overflow-x:auto; max-width:70vw!important; margin-right:5px!important;">
                            <form action="{{route('hiroiSetubiKani.store')}}" method="POST" id="hiroi-store"  enctype="multipart/form-data">
                                <button type="submit" disabled style="display: none;"></button>
                                @csrf
                                <table id="modalTable" style="overflow:hidden;">
                                    

                                </table>
                                <input type="hidden" name="mitumoriId" id="mitumoriId" value="{{ $mitumori->id }}">
                                <input type="hidden" name="sizaiCode" id="sizaiCode" value="">
                            </form>
                            <table id="modalTable2">
                               

                            </table>
                        </div>
               </div>
             
            </div>
            <!-- Modal footer -->
            <div style="overflow-y:auto; max-height:13vh!important" class="  border-t  w-full border-gray-200 rounded-b dark:border-gray-600">
                <button nowrap style="position: sticky; top: 8px;" data-modal-hide="hiroi-modal" type="button" class="h-12 my-2 py-2.5 px-4 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">閉じる</button>
                <button nowrap style="position: sticky; top: 8px;" form="hiroi-store" type="submit" class="my-2 py-2.5 px-4 h-12 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">登録</button>
                <div style="" class="max-w-[390px] w-full float-right items-right">
                    <table class=" h-full max-w-[390px] w-full float-right items-right" id="hiroiTable">
                        
                    </table>
                </div>
            </div>
            </div>
          </div>
      </div>
  