<!-- Main modal -->
  <div id="print-modal" data-modal-backdrop="print" tabindex="-1" aria-hidden="true" class="backdrop-blur-[2px] hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
      <div class="relative p-4 w-300 max-h-full">
          <!-- Modal content -->
          <div style="background-color: #ffffffad;" class="relative bg-gray-100 rounded-lg shadow dark:bg-gray-700">
              <!-- Modal header -->
              <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                  <h3 id="sizaiH" class="text-xl font-semibold text-gray-900 dark:text-white">
                      印刷
                  </h3>
                  <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="print-modal">
                      <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                      </svg>
                      <span class="sr-only">Close modal</span>
                  </button>
              </div>

                <div class="grid grid-flow-col gap-0 px-0">
                <!-- Modal body -->

                <div class="relative overflow-y-auto max-h-[400px]">
                    <table class="">
                        <thead style=" position: sticky;top: 0;">
                            <tr>
                                <th class="w-32 py-2 text-center mitumori-header text-xs">会社名</th>
                                <th class="w-32 py-2 text-center mitumori-header text-xs">目標率</th>
                                <th class="w-32 py-2 text-center mitumori-header text-xs">変動幅</th>
                                <th class="w-32 py-2 text-center mitumori-header text-xs" colspan="2">印刷ボタン</th>
                            </tr>
                        </thead>
                        
                        @if(isset($companies))
                            @foreach($companies as $key=>$company)
                                <tr>
                                    <td class="border border-gray-400 py-2 text-center text-xs">{{$company->name}}</td>
                                    <td class="border border-gray-400 py-2 text-center text-sm">{{$company->mokuhyo_rate}}</td>
                                    <td class="border border-gray-400 py-2 text-center text-sm">{{$company->hendo_rate}}</td>
                                    @if($company->my_flg == 1)
                                        <td class="py-0  px-0 text-center w-20">
                                                <form id="{{ 'pdfForm_'.$key }}" target="_blank" action="{{route('mitumoriSetubiKani.pdf',['id'=>$mitumori->id,'companyId'=>$company->id,'pdfType'=>1])}}" method="GET">
                                                    <button class="text-sm bg-blue-100 border border-gray-400 hover:bg-blue-200 h-10 w-full float-right block" type="submit">
                                                        PDF
                                                    </button>
                                                </form>
                                        </td>
                                        <td class="py-0 px-0 text-center w-20">
                                            <form id="{{ 'excelForm_'.$key }}" action="{{route('mitumoriSetubiKani.excel',['id'=>$mitumori->id,'companyId'=>$company->id,'pdfType'=>1])}}" method="GET">
                                                    <button class="text-sm bg-blue-100 border border-gray-400 hover:bg-blue-200 h-10 w-full float-right block" type="submit">
                                                        Excel
                                                    </button>
                                            </form>
                                        </td>
                                    @else
                                        <td colspan="2" class="py-0 text-center">
                                            <form id="{{ 'pdfForm_'.$key }}" action="{{route('mitumoriSetubiKani.aimitu',['id'=>$mitumori->id,'companyId'=>$company->id])}}" method="GET">
                                                <button class="text-sm bg-blue-100 border border-gray-400 hover:bg-blue-200 h-10 w-full float-right block" type="submit">
                                                    相見積
                                                </button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @endif
                    </table>
                </div>
                </div>  
                <!-- Modal footer -->
                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button data-modal-hide="print-modal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">閉じる</button>
                </div>
            </div>
          </div>
      </div>
  