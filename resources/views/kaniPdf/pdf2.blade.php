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
    <p style="position: absolute; top: -20px; left: 40px;">

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
            <span style="font-size: 20px; float:right;">{{ number_format(($mitumoriSais->sum("gaku"))*(1+$mitumori->tax_rate/100)) }}&nbsp;(税込)&nbsp;</span>
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
            <Th class="tableHeader" style="font-weight:normal; width:80px;">備考</Th>
        </TR>
        </THEAD>
        @foreach ($mitumoriSais as $key=>$mitumoriSai)
            <TR>
                <TD nowrap style="position:relative; font-size:10px; overflow:hidden; max-width:290px;">
                    {{-- @if($mitumoriSai->hiyo_kbn==0)
                    <div style="float:left;">
                        {{ $mitumoriSai->gyoNo }}.
                    </div>
                    @endif
                    <div style="width:160px; float:left; ovarfler">{{ $mitumoriSai->name }}</div>
                    <div style=" text-align:center; float:left;">{{ $mitumoriSai->siyo }}</div> --}}
                    {!! $mitumoriSai->noNameSiyo !!}
                </TD>
                <TD style="text-align:right;">{{ $mitumoriSai->su }}</TD>
                <TD style="text-align:center;">{{ $mitumoriSai->tani }}</TD>
                <TD style="text-align:right;">{{ $mitumoriSai->tanka==0?"":number_format($mitumoriSai->tanka) }}</TD>
                <TD style="text-align:right;">{{ number_format($mitumoriSai->gaku) }}</TD>
                <TD style="overflow:hidden; max-width:80px;" nowrap>{{ $mitumoriSai->biko }}</TD>
            </TR>
        @endforeach
        
        <TR>
            <TD style="border: none;"></TD>
            <TD style="border: none;"></TD>
            <TD style="border: none;"></TD>
            <TD style="text-align:center;background-color: #f2f2f2;">【合計】</TD>
            <TD style="text-align:right;">{{number_format($mitumoriSais->sum("gaku"))}}</TD>
            <TD style="border: none;"></TD>
        </TR>
        <TR>
            <TD style="border: none;"></TD>
            <TD style="border: none;"></TD>
            <TD style="border: none;"></TD>
            <TD style="text-align:center;background-color: #f2f2f2;">消費税（{{$mitumori->tax_rate}}%）</TD>
            <TD style="text-align:right;">{{number_format(($mitumoriSais->sum("gaku"))*($mitumori->tax_rate/100))}}</TD>
            <TD style="border: none;"></TD>
        </TR>
        <TR>
            <TD style="border: none;"></TD>
            <TD style="border: none;"></TD>
            <TD style="border: none;"></TD>
            <TD style="text-align:center;background-color: #f2f2f2;">【総合計】</TD>
            <TD style="text-align:right;">{{number_format(($mitumoriSais->sum("gaku"))*(1+$mitumori->tax_rate/100))}}</TD>
            <TD style="border: none;"></TD>
        </TR>
        @for ($i=$mitumoriSais->count(); $i <= 18; $i++)
            <TR>
                <TD style="border: none;">&nbsp;</TD>
                <TD style="border: none;"></TD>
                <TD style="border: none;"></TD>
                <TD style="border: none;"></TD>
                <TD style="border: none;"></TD>
                <TD style="border: none;"></TD>
            </TR>
        @endfor
        
    </TABLE>

    <p style="margin-top: 7px; font-size:15px;">{!! nl2br(e($mitumori->biko)) !!}</p>
    

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