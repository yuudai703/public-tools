<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>電子承認システム -  {{Config::get('global.company')}}</title>

        <!-- Bootstrap -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
        
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        
        <!-- JS-->
        <script src="//code.jquery.com/jquery-1.10.2.js"></script>
        <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
        <script src="/js/build/jquery.datetimepicker.full.min.js"></script>
        <link rel="stylesheet" href="/css/jquery.datetimepicker.css">
    </head>
    <body>
        <style>
        

        @media print{
            th{
                text-align:center;
                background-color: #CCCCCC!important;
            }   
        }
        body{
            font-size:10px;
            -webkit-print-color-adjust: exact;
        }
        th{
            text-align:center;
            background-color: gray;
        }
        th,td{
            border:1px solid black;
        }
        .td0{
           width: 30px!important;
           text-align:center;
        }
        .td1{
           width: 80px!important;
           text-align:center;
        }
        .td2{
           width: 140px!important;
           text-align:center;
        }
        .td3{
           width: 120px!important;
           text-align:center;
        }
        .td4{
           padding:0px 3px 0px 3px;
            
        }
        /*td1{*/
        /*   width: 60px; */
        /*}*/
        /*td2{*/
        /*    width: 60px;*/
        /*}*/
        /*td3{*/
        /*    width: 60px;*/
        /*}*/
        /*td4{*/
            
        /*}*/
        </style>
        <h4 style="text-align:center;">原価管理表</h4>
        <div style="display:flex; justify-content:space-between;">
            <div>
                <p style="border-bottom:1px solid black;">
                    <span style="text-align:left!important; width:80px; display:inline-block!important;">現場名:</span>
                    <span style="text-align:center!important; width:240px; display:inline-block!important;">{{$target->name}}</span>
                </p>
            </div>
            <table class="sumTable" style="float:right;">
              <tr>
                <td style="white-space: nowrap;"><span style="text-align:right!important; width:70px; display:inline-block!important;">材料費合計:</span><span style="text-align:right!important; width:180px; display:inline-block!important;">{{number_format($sumZai)}}&nbsp;&nbsp;円&nbsp;</span></td>
              </tr>
              <tr>
                <td style="white-space: nowrap;"><span style="text-align:right!important; width:70px; display:inline-block!important;">外注費合計:</span><span style="text-align:right!important; width:180px; display:inline-block!important;">{{number_format($sumGai)}}&nbsp;&nbsp;円&nbsp;</span></td>
              </tr>
              <tr>
                <td style="white-space: nowrap;"><span style="text-align:right!important; width:70px; display:inline-block!important;">経費合計:</span><span style="text-align:right!important; width:180px; display:inline-block!important;">{{number_format($sumKei)}}&nbsp;&nbsp;円&nbsp;</span></td>
              </tr>
              <tr>
                <td style="white-space: nowrap;"><span style="text-align:right!important; width:70px; display:inline-block!important;">労務費合計:</span><span style="text-align:right!important; width:180px; display:inline-block!important;">{{number_format($sumRomu)}}&nbsp;&nbsp;円&nbsp;</span></td>
              </tr>
              <tr>
                <td style="white-space: nowrap;"><span style="text-align:right!important; width:70px; display:inline-block!important;">総合計:</span><span style="text-align:right!important; width:180px; display:inline-block!important;">{{number_format($sumZai+$sumGai+$sumKei+$sumRomu)}}&nbsp;&nbsp;円&nbsp;</span></td>
              </tr>
            </table>
        </div>
        <p style="margin-bottom:0px;">材料費</p>
        <table style="width:100%;">
          <tr>
            <th class="td0">No.</th>
            <th class="td1">日付</th>
            <th class="td2">仕入先名</th>
            <th class="td3" style="text-align:center!important;">金額（税込）</th>
            <th class="td4">備考</th>
          </tr>
            @forEach($zais as $key=>$zai)
            <tr>
                <td class="td0">{{$key+1}}</td>
                <td class="td1">{{$zai->target_at}}</td>
                <td class="td2">{{$zai->torisaki_nm}}</td>
                <td class="td3" style="text-align:right!important;">{{number_format($zai->gaku)}}&nbsp;円&nbsp;&nbsp;</td>
                <td class="td4">{{$zai->biko}}</td>
            </tr>
          @endforEach
          <tr>
                <td style="border:none;"></td>
                <td style="border:none;"></td>
                <td style="border:none;"></td>
                <td><span style="text-align:right!important; width:30px; display:inline-block!important;">合計:</span><span style="text-align:right!important; width:83px; display:inline-block!important;">{{number_format($sumZai)}}&nbsp;円</span></td>
                <td style="border:none;"></td>
            </tr>
        </table>
        
        <p style="margin-bottom:0px;">外注費</p>
        <table style="width:100%;">
          <tr>
            <th class="td0">No.</th>
            <th class="td1">日付</th>
            <th class="td2">外注先名</th>
            <th class="td3">金額（税込）</th>
            <th class="td4">備考</th>
          </tr>
          @if(isset($gais))
            @forEach($gais as $key=>$gai)
            <tr>
                <td class="td0">{{$key+1}}</td>
                <td class="td1">{{$gai->target_at}}</td>
                <td class="td2">{{$gai->torisaki_nm}}</td>
                <td class="td3" style="text-align:right!important;">{{number_format($gai->gaku)}}&nbsp;円&nbsp;&nbsp;</td>
                <td class="td4">{{$gai->biko}}</td>
            </tr>
            @endforEach
            <tr>
                <td style="border:none;"></td>
                <td style="border:none;"></td>
                <td style="border:none;"></td>
                <td><span style="text-align:right!important; width:30px; display:inline-block!important;">合計:</span><span style="text-align:right!important; width:83px; display:inline-block!important;">{{number_format($sumGai)}}&nbsp;円</span></td>
                <td style="border:none;"></td>
            </tr>
          @endif
        </table>
        <p style="margin-bottom:0px;">経費</p>
        <table style="width:100%;">
          <tr>
            <th class="td0">No.</th>
            <th class="td1">日付</th>
            <th class="td2">相手先名</th>
            <th class="td3" style="text-align:center!important;">金額（税込）</th>
            <th class="td4">備考</th>
          </tr>
          @if(isset($keis))
            @forEach($keis as $key=>$kei)
            <tr>
                <td class="td0">{{$key+1}}</td>
                <td class="td1">{{$kei->target_at}}</td>
                <td class="td2">{{$kei->torisaki_nm}}</td>
                <td class="td3" style="text-align:right!important;">{{number_format($kei->gaku)}}&nbsp;円&nbsp;&nbsp;</td>
                <td class="td4">{{$kei->biko}}</td>
            </tr>
            @endforEach
            <tr>
                <td style="border:none;"></td>
                <td style="border:none;"></td>
                <td style="border:none;"></td>
                <td><span style="text-align:right!important; width:30px; display:inline-block!important;">合計:</span><span style="text-align:right!important; width:83px; display:inline-block!important;">{{number_format($sumKei)}}&nbsp;円</span></td>
                <td style="border:none;"></td>
            </tr>
          @endif
        </table>
        
        <p style="margin-bottom:0px;">労務費</p>
        <table style="width:100%;">
            <tr>
                <th class="td0">No.</th>
                <th class="td1">日付</th>
                <th class="td2">詳細</th>
                <th class="td3" style="text-align:center!important;">金額（税込）</th>
                <th class="td4">備考</th>
             </tr>
          @if(isset($romus))
            @forEach($romus as $key=>$romu)
            <tr>
                <td class="td0">{{$key+1}}</td>
                <td class="td1">{{$romu->target_at}}</td>
                <td class="td2" style="text-align:center!important;">
                  @if($romu->Agaku!=0)
                    {{'35000'.'×'.($romu->Agaku/35000)}}
                 @endif
                  @if($romu->Agaku!=0 && $romu->Bgaku!=0)
                      &nbsp;&nbsp;&nbsp;
                 @endif
                 @if($romu->Bgaku!=0)
                   {{'30000'.'×'.($romu->Bgaku/30000)}}
                 @endif</td>
                <td class="td3" style="text-align:right!important;">{{number_format($romu->gaku)}}&nbsp;円&nbsp;&nbsp;</td>
                <td class="td4">{{$userName[$romu->target_at]}}</td>
            </tr>
            @endforEach
            <tr>
                <td style="border:none;"></td>
                <td style="border:none;"></td>
                <td style="border:none;"></td>
                <td><span style="text-align:right!important; width:30px; display:inline-block!important;">合計:</span><span style="text-align:right!important; width:83px; display:inline-block!important;">{{number_format($sumRomu)}}&nbsp;円</span></td>
                <td style="border:none;"></td>
            </tr>
          @endif
        </table>
    </body>
    
    <script>
        window.print();
    </script>
</html>