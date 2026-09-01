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
            margin: 50px 0px 40px 0px;
            size: landscape; 
        }

        /* 全てのHTML要素に適用 */
        html, body, textarea, table {
            font-family: 'NotoSansJP', sans-serif;
        }
        body {
            padding-top: 0mm;
            width: 250mm;
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
    {{-- 下に表示される --}}
    <p style="margin-top: 7px; position: absolute; top: 970px; left: 40px; font-size:10px;">
        <span style="vertical-align: top; ">備考:</span>
        <span style="display: inline-block; line-height: 0.9; vertical-align: top;">
            {!! nl2br(e($mitumori->biko)) !!}
        </span>
    </p>

    <p style="background-color:black; color:white; font-size:32px; border-radius:10px; height:50px; line-height:28px; text-align:center; margin-top: 0px; margin-bottom: 0px!important; letter-spacing: 20px;">&nbsp;見積書</p>
    <p style="text-align:right; font-size:14px; margin:0px !important;">{{ $today }}</p>
    <div class="header clearfix">
        <div class="left">
            <p style="font-size:15px; height:34px; margin:0px; border-bottom:1px solid black; width:450px; padding-bottom:2px;"><span style="float:left; white-space: pre; position: relative;">{!! $mitumori->atesaki !!}</span><span style="float:right;">{{ $mitumori->keisyo }}&nbsp;</span></p>
            <p style="font-size:15px; height:34px; margin:0px; border-bottom:1px solid black; width:450px; position:relative;">&nbsp;工事件名&nbsp;{!! $mitumori->title !!}</p>
            <p style="font-size:15px; height:34px; margin:0px; border-bottom:1px solid black; width:450px; position:relative;">&nbsp;施工場所&nbsp;{!! $mitumori->area !!}</p>
            <p style="font-size:15px; height:34px; margin:0px; border-bottom:1px solid black; width:450px; position:relative;">&nbsp;取引方法&nbsp;{!! $mitumori->torihikiho !!}</p>
            <p style="font-size:15px; height:34px; margin:0 0 30px 0; border-bottom:1px solid black; width:450px; position:relative;">&nbsp;有効期限&nbsp;{!! $mitumori->kigen !!}</p>
            <div style="border-bottom: 1px solid #000; height:45px; line-height: 23px; width: 407px; margin:0px; padding:0px 20px;">
                <span style="font-size: 16px; float:left;">御見積り金額</span>
                <span style="font-size: 23px; float:right;">￥{{ number_format(($mitumoriSais->sum("gaku"))*(1+$mitumori->tax_rate/100)) }}&nbsp;(税込)&nbsp;</span>
            </div>
        </div>
        <div class="right" style="width:80px;">
        </div>
        <div class="right">
            <p>
                <span style="font-size:20px;" >{{ $company->name }}</span><br>
                <span style="font-size:17px;">&nbsp;&nbsp;{{ $company->post }}</span>&nbsp;<span style="font-size:17px;">{{ $company->delegate }}</span><br>
                <span style="font-size:17px;">〒 {{ $company->yubin_no }}</span><br>
                <span style="font-size:17px;">{{ $company->address }}</span>
                <span style="font-size:14px; height:15px; display:block;">TEL {{ $company->phone }}</span>
                <span style="font-size:14px; height:15px; display:block;">FAX {{ $company->fax }}</span>
            </p>

            <div style="height: 30px; position:relative; text-align:center; font-size:16px; border-bottom:1px rgb(0, 0, 0) solid; display:block; ">
                <span style="font-size:12px; position: absolute; top:7px;">担当</span>
                <span style="font-size:16px;">{{ $mitumori->tantoName }}</span>
            </div>
        </div>
    </div>
    
    <TABLE class="price" style="margin-top: 0;">
        <thead style=" color:white;">
            <TR>
                <Th class="tableHeader" style="background-color:black; font-weight:normal; width:420px;">品名</Th>
                <Th class="tableHeader" style="background-color:black; font-weight:normal; width:40px;">数量</Th>
                <Th class="tableHeader" style="background-color:black; font-weight:normal; width:40px;">単位</Th>
                <Th class="tableHeader" style="background-color:black; font-weight:normal; width:75px;">単価</Th>
                <Th class="tableHeader" style="background-color:black; font-weight:normal; width:100px;">金額</Th>
                <Th class="tableHeader" style="background-color:black; font-weight:normal; ">備考</Th>
            </TR>
        </thead>
        @foreach ($mitumoriSais as $key=>$mitumoriSai)
            <TR>
                <TD style="position:relative; font-size:10px; overflow:hidden; max-width:420px;">
                    {!! $mitumoriSai->noNameSiyo !!}
                </TD>
                <TD style="text-align:right;">{{ $mitumoriSai->su }}</TD>
                <TD style="text-align:center;">{{ $mitumoriSai->tani }}</TD>
                <TD style="text-align:right;">{{ $mitumoriSai->hiyo_kbn!=0?"":number_format($mitumoriSai->tanka) }}</TD>
                <TD style="text-align:right;">{{ number_format($mitumoriSai->gaku) }}</TD>
                <TD style="overflow:hidden; max-width:244px">{{ $mitumoriSai->biko }}</TD>
            </TR>
            @if($key==13)
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
            <TR  @if($mitumoriSaisCount==14) style="page-break-after: always;" @endif>
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
        <TR @if($mitumoriSaisCount==14) style="page-break-after: always;" @endif>
            <TD>【合計】</TD>
            <TD></TD>
            <TD></TD>
            <TD></TD>
            <TD style="text-align:right;">{{number_format($mitumoriSais->sum("gaku"))}}</TD>
            <TD></TD>
        </TR>
        <?PHP $mitumoriSaisCount+=1;?>
        <TR @if($mitumoriSaisCount==14) style="page-break-after: always;" @endif>
            <TD style="font-size:10px;">&nbsp;&nbsp;消費税（{{$mitumori->tax_rate}}%）</TD>
            <TD></TD>
            <TD></TD>
            <TD></TD>
            <TD style="text-align:right;">{{number_format(($mitumoriSais->sum("gaku"))*($mitumori->tax_rate/100))}}</TD>
            <TD></TD>
        </TR>
        <?PHP $mitumoriSaisCount+=1;?>
        <TR @if($mitumoriSaisCount==14) style="page-break-after: always;" @endif>
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

            if(($mitumoriSais->count()+$etcRowCount)<=14){
                $max=13;
                $start=$mitumoriSais->count()+$etcRowCount;
            }else{
                // 2ページ目以降の処理
                $twoPageDataCount=($mitumoriSais->count()+$etcRowCount)-14;
                $twoPageDataCountMod=$twoPageDataCount%28;//最大行数のあまり

                //forは0からカウントされるため２８ではなく２７がマックス、
                // だけど０の時はデータ数が０のためforは実行させない。
                $max=27;//最大行数―最後の合計、消費税、総合計の行の値
                
                $start=$twoPageDataCountMod;
            }
        ?>
        {{-- {{ dd($start,$twoPageDataCount,$twoPageDataCountMod) }} --}}
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
            $x = 405;
            $y = 570;
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