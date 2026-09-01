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
            margin: 30px 0px 100px 0px;
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
    
    <p style="position: absolute; top: 960px; left: 75px; font-size:15px;">
        <span style="vertical-align: top; ">備考:</span>
            <span style="display: inline-block; line-height: 0.9; vertical-align: top;">
                {!! nl2br(e($mitumori->biko)) !!}
            </span>
    </p>

    <p style="font-size:22px; border-bottom:1px solid black; margin-top: 0px; margin-bottom: 0px!important; letter-spacing: 10px;">御見積書</p>
    <p style="text-align:right; font-size:14px; margin:0px !important;">{{ $today }}</p>
    <div class="header clearfix">
        <div class="left">
            <p style="font-size:15px; height:34px; margin:0px; width:390px; padding-bottom:2px;"><span style="float:left; white-space: pre; position: relative;">{!! $mitumori->atesaki !!}</span><span style="float:right;">{{ $mitumori->keisyo }}&nbsp;</span></p>
            <p style="font-size:15px; height:34px; margin:0px; width:450px; position:relative;">工事件名：{!! $mitumori->title !!}</p>
            <p style="font-size:15px; height:34px; margin:0px; width:450px; position:relative;">施工場所：{!! $mitumori->area !!}</p>
            <p style="font-size:15px; height:34px; margin:0px; width:450px; position:relative;">有効期限：{!! $mitumori->kigen !!}</p>
            <p style="font-size:15px; height:34px; margin:0px; width:450px; position:relative;">取引方法：{!! $mitumori->torihikiho !!}</p>
            
        </div>
        <div class="right">
            
            <p>
                <span style="font-size:20px;">{{ $company->name }}</span><br>
                <span>&nbsp;&nbsp;{{ $company->post }}</span>&nbsp;<span>{{ $company->delegate }}</span><br>
                <span>〒 {{ $company->yubin_no }}</span><br>
                <span>{{ $company->address }}</span><br>
                <span style="height:14px; display:block;">TEL {{ $company->phone }}</span>
                <span style="height:14px; display:block;">FAX {{ $company->fax }}</span>
            </p>

            <div style="height: 30px; position:relative; text-align:center; font-size:16px; border-bottom:1px rgb(0, 0, 0) solid; display:block; ">
                <span style="font-size:12px; position: absolute; top:7px;">担当</span>
                <span style="font-size:16px;">{{ $mitumori->tantoName }}</span>
            </div>
        </div>
    </div>
    <div class="right" style="border-bottom: 1px solid #000; height:45px; line-height: 23px; width: 407px; margin:0px; padding:0px 20px;">
            <span style="font-size: 20px; float:left;">御見積金額</span>
            <span style="font-size: 20px; float:right;">{{ number_format($sumGaku*(1+$mitumori->tax_rate/100)) }}.-&nbsp;(税込)&nbsp;</span>
    </div>
    <br>
    <br>
    <br>
    
    <TABLE class="price" style="margin-top: 0;">
        <THEAD>
            <TR>
                <Th class="tableHeader" style="font-weight:normal; width:290px;">品名</Th>
                <Th class="tableHeader" style="font-weight:normal; width:35px;">数量</Th>
                <Th class="tableHeader" style="font-weight:normal; width:35px;">単位</Th>
                <Th class="tableHeader" style="font-weight:normal; width:80px;">単価</Th>
                <Th class="tableHeader" style="font-weight:normal; width:90px;">金額</Th>
                <Th class="tableHeader" style="font-weight:normal;">備考</Th>
            </TR>
        </THEAD>
        @foreach ($mitumoriKos as $key=>$mitumoriKo)
            <TR>
                <TD nowrap style="max-width:290px; overflow:hidden;">@if($mitumoriKo->hiyo_kbn==0) {{ $mitumoriKo->gyoNo }}. @endif {{ $mitumoriKo->name }}</TD>
                <TD style="text-align:right;">{{ $mitumoriKo->su }}</TD>
                <TD style="text-align:center;">{{ $mitumoriKo->tani }}</TD>
                <TD style="text-align:right;">{{ $mitumoriKo->hiyo_kbn!=0 && $mitumoriKo->hiyo_kbn!=2?"":number_format($mitumoriKo->tanka)}}</TD>
                <TD style="text-align:right;">{{ number_format($mitumoriKo->gaku) }}</TD>
                <TD style="max-width:80px; overflow:hidden;">{{ $mitumoriKo->biko }}</TD>
            </TR>
        @endforeach
        <TR>
            <TD style="border:none;"></TD>
            <TD style="border:none;"></TD>
            <TD style="border:none;"></TD>
            <TD class="tableHeader" style="text-align:center; background-color: #f2f2f2;">【合計】</TD>
            <TD style="text-align:right;">{{number_format($sumGaku)}}</TD>
            <TD style="border:none;"></TD>
        </TR>
        <TR>
            <TD style="border:none;"></TD>
            <TD style="border:none;"></TD>
            <TD style="border:none;"></TD>
            <TD class="tableHeader" style="text-align:center; background-color: #f2f2f2;">消費税（{{$mitumori->tax_rate}}%）</TD>
            <TD style="text-align:right;">{{number_format($sumGaku*$mitumori->tax_rate/100)}}</TD>
            <TD style="border:none;"></TD>
        </TR>
        <TR>
            <TD style="border:none;"></TD>
            <TD style="border:none;"></TD>
            <TD style="border:none;"></TD>
            <TD class="tableHeader" style="text-align:center; background-color: #f2f2f2;">【総合計】</TD>
            <TD style="text-align:right;">{{ number_format($sumGaku*(1+$mitumori->tax_rate/100)) }}</TD>
            <TD style="border:none;"></TD>
        </TR>
    </TABLE>
    <div style="page-break-after: always;"></div>
    
    
    
    <TABLE class="price" style="margin-top: 0;">
        <thead>
            <TR>
                <Th colspan="6" style="font-weight:normal; text-align:left; height:50px; border-bottom:1px solid black; font-size:25px; letter-spacing: 5px; padding-bottom:0px;">内訳書 </Th>
            </TR>
            <TR>
                <Th colspan="6" style="height:10px;"></Th>
            </TR>
            <TR>
                <Th class="tableHeader" style="font-weight:normal; width:290px;">品名</Th>
                <Th class="tableHeader" style="font-weight:normal; width:35px;">数</Th>
                <Th class="tableHeader" style="font-weight:normal; width:35px;">単位</Th>
                <Th class="tableHeader" style="font-weight:normal; width:80px;">単価</Th>
                <Th class="tableHeader" style="font-weight:normal; width:90px;">金額</Th>
                <Th class="tableHeader" style="font-weight:normal;">備考</Th>
            </TR>
        </thead>
        @foreach ($mitumoriSais as $key=>$mitumoriSai)
            {{-- 見積項目名 --}}
            <TR>
                <TD nowrap style="max-height:20px; overflow:hidden; max-width:320px;">{{ $mitumoriSai[0]->koGyoNo.'.'.$mitumoriSai[0]->koName }}</TD>
                <TD></TD>
                <TD></TD>
                <TD></TD>
                <TD></TD>
                <TD></TD>
            </TR>
            {{-- ここまで --}}

            {{-- 見積内訳 --}}
            @foreach ($mitumoriSai as $key=>$m)
                <TR nowrap style="max-height:20px!important; overflow:hidden;">
                    <TD style="position:relative; font-size:10px; max-height: 20px; white-space: nowrap; overflow:hidden; max-width:290px;">
                        {{-- <div style="width:160px; float:left;">{{ $m->name }}</div>
                        <div style=" text-align:center; float:left;">{{ $m->siyo }}dd</div> --}}
                        {!! $m->nameSiyo !!}
                    </TD>
                    <TD style="text-align: right;">{{ $m->su }}</TD>
                    <TD style="text-align: center;">{{ $m->tani }}</TD>
                    <TD style="text-align: right;">{{ number_format($m->tanka) }}</TD>
                    <TD style="text-align: right;">{{ number_format($m->gaku) }}</TD>
                    <TD style="text-align: left; max-width:80px; overflow:hidden;">{{ $m->biko }}</TD>
                </TR>
            @endforeach
            {{-- ここまで --}}

            {{-- 労務費 --}}
            @if($mitumoriSai[0]->koRomGaku<>0)
            <TR>
                <TD>労務費</TD>
                <TD style="text-align:right;">1</TD>
                <TD style="text-align:center;">式</TD>
                <TD></TD>
                <TD style="text-align:right;">{{number_format(round($mitumoriSai[0]->koRomGaku)) /*何でもいい*/}}</TD>
                <TD></TD>
            </TR>
            @endif
            {{-- ここまで --}}


            {{-- 内訳合計 --}}
            <TR>
                <TD>【合計】</TD>
                <TD></TD>
                <TD></TD>
                <TD></TD>
                <TD style="text-align:right;">{{number_format($mitumoriSai->sum("gaku")+round($mitumoriSai[0]->koRomGaku))}}</TD>
                <TD></TD>
            </TR>
            {{-- ここまで --}}

            {{-- 余った行 --}}
            <?PHP
                if($mitumoriSai[0]->koRomGaku<>0){
                //data数＋項目名行＋労務費行＋合計行
                    $etcRowCount=3;
                }else{
                //労務単価入れない時
                //data数＋項目名行+合計行
                    $etcRowCount=2;
                }
                // data数＋項目名行＋労務費行＋合計行
                $mitumoriSaiCount = $mitumoriSai->count()+$etcRowCount;
                $start = $mitumoriSaiCount%36;
                $end = 35;

            ?>
            @for ($i=$start; $i <= $end; $i++)
            {{-- <TR @if($i==35) style="page-break-after: always;" @endif> --}}
            <TR>
                <TD style="max-height:20px!important;">&nbsp;</TD>
                <TD></TD>
                <TD></TD>
                <TD></TD>
                <TD></TD>
                <TD></TD>
            </TR>
            @endfor
            {{-- ここまで --}}
            

        @endforeach
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