<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>御見積書</title>
    <style>
        /* 基本の文字 */
        @font-face {
            font-family: 'NotoSansJP';
            font-style: normal;
            font-weight: normal;
            src: url('{{ storage_path('fonts/NotoSansJP-Regular.ttf') }}');
        }

        @page {
            margin: 30px 0px 50px 0px;
        }

        /* 全てのHTML要素に適用 */
        html, body, textarea, table {
            font-family: 'NotoSansJP', sans-serif;
        }
        body {
            padding-top: 5mm;
            width: 170mm;
            height: 297mm;
            margin-left: auto;
            margin-right: auto; 
            font-size: 12px;
        }

        .header,
        .footer {
            width: 100%;
            overflow: hidden;
        }

        .left,
        .right {
            width: 48%;
            box-sizing: border-box;
        }

        .left {
            float: left;
            width: 60%;
        }

        .right {
            float: right;
            width: 200px;
        }

        .clearfix:after {
            content: "";
            display: table;
            clear: both;
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        td {
            border: 1px solid #000;
            padding: 0px 7px;
        }
        
        
        
        th.tableHeader {
            border: 1px solid #000;
            background-color: #f2f2f2;
        }

        .total {
            text-align: right;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    {{-- 下に表示される --}}

<div style="position: absolute; top: 980px; left: 75px; font-size:15px;">
    <span style="vertical-align: top; ">備考:</span>
    <span style="display: inline-block; line-height: 0.9; vertical-align: top;">
        {!! nl2br(e($mitumori->biko)) !!}
    </span>
</div>

    <p style="font-size:32px; text-align:center; margin-top: 0px; margin-bottom: 0px!important; letter-spacing: 60px;">&nbsp;御見積書</p>
    <p style="text-align:right; font-size:14px; margin:0px !important;">{{ $today }}</p>
    @if($COM_stamp)
        <img src="data:image/png;base64,{{ $COM_stamp }}" style="position: absolute; top: 115px; right: 72px; width:70px"><!--　1mm>4px  18mm -->
    @endif
    @if($PIC_stamp)
        <img src="data:image/png;base64,{{ $PIC_stamp }}" style="position: absolute; top: 340px; right: 94px; width:42px"><!--　1mm>4px  10.5mm -->
    @endif
    <div class="header clearfix">
        <div class="left">
            <p style="font-size:19px; height:34px; margin:0px; border-bottom:1px solid black; width:100%; padding-bottom:2px; overflow:hidden;"><span style="float:left; white-space: pre; position: relative;">{!! $mitumori->atesaki !!}</span><span style="float:right;">{{ $mitumori->keisyo }}&nbsp;</span></p>
            <p style="font-size:15.5px;  height:34px; margin:0px;  border-bottom:1px solid black; width:100%; position: relative; white-space: pre;">工事件名&nbsp;&nbsp;&nbsp;{!! $mitumori->title !!}</p>
            <p style="font-size:15.5px; height:34px; margin:0px; border-bottom:1px solid black; width:100%;  position: relative; white-space: pre;">施工場所&nbsp;&nbsp;&nbsp;{!! $mitumori->area !!}</p>
            <p style="font-size:15.5px; height:34px; margin:0px; border-bottom:1px solid black; width:100%;  position: relative; white-space: pre;">取引方法&nbsp;&nbsp;&nbsp;{!! $mitumori->torihikiho !!}</p>
            <p style="font-size:15.5px; height:34px; margin:0 0 30px 0; border-bottom:1px solid black; width:100%;  position: relative; white-space: pre;">有効期限&nbsp;&nbsp;&nbsp;{!! $mitumori->kigen !!}</p>
            <div style="border: 1px solid #000; background-color:#d1d1d1; height:45px; line-height: 23px; width: 90%; margin:0px; padding:0px 20px;">
                <span style="font-size: 16px; float:left;">御見積金額</span>
                <span style="font-size: 16px; float:right;">￥&nbsp;{{ number_format(($mitumoriSais->sum("gaku"))*(1+$mitumori->tax_rate/100)) }}.-&nbsp;(税込)
                   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
            </div>
        </div>
        <div class="right">
            
            <p>
                <span style="font-size:20px;">{{ $company->name }}</span><br>
                <span>&nbsp;&nbsp;{{ $company->post }}</span>&nbsp;<span>{{ $company->delegate }}</span><br>
                <span>〒 {{ $company->yubin_no }}</span><br style="height:0px;">
                <span>{{ $company->address }}</span><br>
                <span style="height:14px; display:block;">TEL {{ $company->phone }}</span>
                <span style="height:14px; display:block;">FAX {{ $company->fax }}</span>
            </p>

            <div style="height: 30px; position:relative; text-align:center; font-size:16px; border-bottom:1px rgb(0, 0, 0) solid; display:block; ">
                <span style="font-size:12px; position: absolute; top:7px;">担当</span>
                <span style="font-size:16px;">{{ $mitumori->tantoName }}</span>
            </div>

            <table style="width:80px" align="right">
                <tr>
                  
                    <td style="height:8px!important; line-height:8px; font-size:10px; padding:0px; text-align:center; background-color:#d1d1d1;">印</td>
                </tr>
                <tr>
                   
                    <td style="border-bottom:1px solid black; height:60px"></td>
                </tr>
            </table>
        </div>
    </div>
    
    <TABLE class="price" style="margin-top: 0;">
        <thead>
            <TR>
                <Th class="tableHeader" style="font-weight:normal; width:290px;">品名</Th>
                <Th class="tableHeader" style="font-weight:normal; width:35px;">数量</Th>
                <Th class="tableHeader" style="font-weight:normal; width:35px;">単位</Th>
                <Th class="tableHeader" style="font-weight:normal; width:80px;">単価</Th>
                <Th class="tableHeader" style="font-weight:normal; width:90px;">金額</Th>
                <Th class="tableHeader" style="font-weight:normal;">備考</Th>
            </TR>
        </thead>
        @foreach ($mitumoriSais as $key=>$mS)
            {{-- @if($mS->su==0 && $mS->tanka==0)
                <TR>
                    <TD colspan="6" style="overflow:hidden; white-space: nowrap; height:22px;">{{ $mS->name }}</TD>
                </TR>
            @else --}}
                <TR>
                    <TD nowrap style="{{  $mS->hiyo_kbn==5?'font-size:11px;':'font-size:10px;'}} width:275px; max-width:275px; max-height:8px; position:relative;">
                        {!! $mS->noNameSiyo !!}
                    </TD>
                    <TD style="text-align:right;">{{ $mS->hiyo_kbn==5?'':$mS->su }}</TD>
                    <TD style="text-align:center;">{{ $mS->hiyo_kbn==5?'':$mS->tani }}</TD>
                    <TD style="text-align:right;">{{ $mS->hiyo_kbn==5?'':number_format($mS->tanka) }}</TD>
                    <TD style="text-align:right;">{{ $mS->hiyo_kbn==5?number_format(abs($mS->gaku)):number_format($mS->gaku) }}</TD>
                    <TD style="overflow:hidden; max-width:80px">{{ $mS->biko }}</TD>
                </TR>
            {{-- @endif --}}
            @if($key==22)
                <TR style="page-break-after: always; border:none;">
                    <TD style="border:none!important;"></TD>
                    <TD style="border:none!important;"></TD>
                    <TD style="border:none!important;"></TD>
                    <TD style="border:none!important;"></TD>
                    <TD style="border:none!important;"></TD>
                    <TD style="border:none!important;"></TD>
                </TR>
            @endif
        @endforeach
        <?PHP
            $mitumoriSaisCount=$mitumoriSais->count();
            //合計や消費税などが１ページ目の最後らへんの時うまく、改ページしないためカウントする
            //カウントが23コ目で開業する
        ?>
        {{-- @if($roumhi<>0)
            <?PHP //$mitumoriSaisCount+=1;?>
            <TR @if($mitumoriSaisCount==23) style="page-break-after: always;" @endif>
                <TD style="font-size:10px;">労務費</TD>
                <TD style="text-align:right;">1</TD>
                <TD style="text_align:center;">式</TD>
                <TD style=""></TD>
                <TD style="text-align:right;">
                    {{$roumhi}}
                </TD>
                <TD></TD>
            </TR>
        @endif --}}
        <?PHP $mitumoriSaisCount+=1;?>
        <TR @if($mitumoriSaisCount==23) style="page-break-after: always;" @endif>
            <TD>【合計】</TD>
            <TD></TD>
            <TD></TD>
            <TD></TD>
            <TD style="text-align:right;">{{number_format($mitumoriSais->sum("gaku"))}}</TD>
            <TD></TD>
        </TR>
        <?PHP $mitumoriSaisCount+=1;?>
        <TR @if($mitumoriSaisCount==23) style="page-break-after: always;" @endif>
            <TD style="font-size:10px;">&nbsp;&nbsp;消費税（{{$mitumori->tax_rate}}%）</TD>
            <TD></TD>
            <TD></TD>
            <TD></TD>
            <TD style="text-align:right;">{{number_format(($mitumoriSais->sum("gaku"))*($mitumori->tax_rate/100))}}</TD>
            <TD></TD>
        </TR>
        <?PHP $mitumoriSaisCount+=1;?>
        <TR @if($mitumoriSaisCount==23) style="page-break-after: always;" @endif>
            <TD>【総合計】</TD>
            <TD></TD>
            <TD></TD>
            <TD></TD>
            <TD style="text-align:right;">{{number_format(($mitumoriSais->sum("gaku"))*(1+$mitumori->tax_rate/100))}}</TD>
            <TD></TD>
        </TR>
        <?PHP
            // if($roumhi<>0){
            //     //合計　消費税　労務費　総合計　４行
            //     $etcRowCount=4;
            // }else{
            //     $etcRowCount=3;
            // }
            $etcRowCount=3;

            if(($mitumoriSais->count()+$etcRowCount)<=23){
                $max=22;
                $start=$mitumoriSais->count()+$etcRowCount;
            }else{
                // 2ページ目以降の処理
                $twoPageDataCount=($mitumoriSais->count()+$etcRowCount)-23;
                $twoPageDataCountMod=$twoPageDataCount%41;

                // //最後の合計、消費税、総合計の行を考慮して
                // if(in_array($twoPageDataCountMod,[40,39,38])){ 
                //     $twoPageDataCountMod=0;
                // }
                $max=40;
                $start=$twoPageDataCountMod;
            }
        ?>
        @if($start<>0)
        @for ($i=$start; $i <= $max; $i++)
            <TR>
                <TD>&nbsp;</TD>
                <TD></TD>
                <TD></TD>
                <TD></TD>
                <TD></TD>
                <TD></TD>
            </TR>
        @endfor
        @endif
        
    </TABLE>

    
    

    <script type="text/php">
            $x = 500;
            $y = 18;
            $text = "Page {PAGE_NUM} / {PAGE_COUNT}";
            $font = $fontMetrics->get_font("helvetica", "solid");
            $size = 8;
            $color = array(0,0,0);
            $word_space = 0.0;  //  default
            $char_space = 0.0;  //  default
            $angle = 0.0;   //  default
            $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
        
    </script>
        
</body>
</html>