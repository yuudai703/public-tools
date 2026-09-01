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
\           height: 297mm;
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
    

<div style="position: absolute; top: 980px; left: 77px; font-size:15px;">
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
{{-- <<<<<<< HEAD --}}
            
            <p style="font-size:19px; height:34px; margin:0px; border-bottom:1px solid black; width:100%; padding-bottom:2px; overflow:hidden;"><span style="float:left; white-space: pre; position: relative;">{!! $mitumori->atesaki !!}</span><span style="float:right;">{{ $mitumori->keisyo }}&nbsp;</span></p>
            <p style="font-size:15.5px;  height:34px; margin:0px;  border-bottom:1px solid black; width:100%; position: relative; white-space: pre;">工事件名&nbsp;&nbsp;&nbsp;{!! $mitumori->title !!}</p>
            <p style="font-size:15.5px; height:34px; margin:0px; border-bottom:1px solid black; width:100%;  position: relative; white-space: pre;">施工場所&nbsp;&nbsp;&nbsp;{!! $mitumori->area !!}</p>
            <p style="font-size:15.5px; height:34px; margin:0px; border-bottom:1px solid black; width:100%;  position: relative; white-space: pre;">取引方法&nbsp;&nbsp;&nbsp;{!! $mitumori->torihikiho !!}</p>
            <p style="font-size:15.5px; height:34px; margin:0 0 30px 0; border-bottom:1px solid black; width:100%;  position: relative; white-space: pre;">有効期限&nbsp;&nbsp;&nbsp;{!! $mitumori->kigen !!}</p>
            <div style="border: 1px solid #000; background-color:#d1d1d1; height:45px; line-height: 23px; width:90%; margin:0px; padding:0px 20px;">
                <span style="font-size: 16px; float:left;">御見積金額</span>
                <span style="font-size: 16px; float:right;">￥&nbsp;{{ number_format($sumGaku*(1+$mitumori->tax_rate/100)) }}.-&nbsp;(税込)
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
{{-- ======= --}}
            {{-- <p style="font-size:21px; height:34px; margin:0px; border-bottom:1px solid black; width:450px; padding-bottom:2px;"><span style="float:left;">{{ $mitumori->caseGroupName }}</span><span style="float:right;">御中&nbsp;&nbsp;</span></p>
            <p style="font-size:17px; height:34px; margin:0px; border-bottom:1px solid black; width:450px;">工事件名&nbsp;{{ $mitumori->title }}</p>
            <p style="font-size:17px; height:34px; margin:0px; border-bottom:1px solid black; width:450px;">施工場所&nbsp;{{ $mitumori->area }}</p>
            <p style="font-size:17px; height:34px; margin:0px; border-bottom:1px solid black; width:450px;">取引方法&nbsp;{{ $mitumori->torihikiho }}</p>
            <p style="font-size:17px; height:34px; margin:0 0 30px 0; border-bottom:1px solid black; width:450px;">有効期限&nbsp;{{ $mitumori->kigen }}</p>
            <div style="border: 1px solid #000; background-color:#d1d1d1; height:45px; line-height: 23px; width: 407px; margin:0px; padding:0px 15px;">
                <span style="font-size: 24px; float:left;">御見積金額</span>
                <span style="font-size: 24px; float:right;">￥{{ number_format($sumGaku*1.1) }}.ー&nbsp;(税込)&nbsp;</span> --}}
{{-- >>>>>>> origin/firesafetycheck_quotations --}}
            </div>
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
        <THEAD>
            <TR>
                <Th class="tableHeader" style="font-weight:normal; width:290px;">品名</Th>
                <Th class="tableHeader" style="font-weight:normal; width:35px;">数量</Th>
                <Th class="tableHeader" style="font-weight:normal; width:35px;">単位</Th>
                <Th class="tableHeader" style="font-weight:normal; width:80px;">単価</Th>
                <Th class="tableHeader" style="font-weight:normal; width:90px;">金額</Th>
                <Th class="tableHeader" style="font-weight:normal; ">備考</Th>
            </TR>
        </THEAD>
        @foreach ($mitumoriKos as $key=>$mitumoriKo)
            {{-- @if($mitumoriKo->su==0 && $mitumoriKo->tanka==0)
                <TR>
                    <TD colspan="6" style="overflow:hidden; white-space: nowrap; height:22px;">{{ $mitumoriKo->name }}</TD>
                </TR>
                @continue
            @endif --}}
            <TR>
                <TD style="overflow:hidden; max-width:290px; white-space: nowrap;">@if($mitumoriKo->hiyo_kbn==0) {{ $mitumoriKo->gyoNo }}. @endif {{ $mitumoriKo->name }}</TD>
                <TD style="text-align:right;">{{ $mitumoriKo->hiyo_kbn!=5?$mitumoriKo->su:'' }}</TD>
                <TD style="text-align:center;">{{ $mitumoriKo->tani }}</TD>
                <TD style="text-align:right;">{{ $mitumoriKo->hiyo_kbn!=0 && $mitumoriKo->hiyo_kbn!=2 && $mitumoriKo->hiyo_kbn!=3 && $mitumoriKo->hiyo_kbn!=4?"":number_format($mitumoriKo->tanka) }}</TD>
                {{-- <TD style="text-align:right;">{{ number_format($mitumoriKo->tanka) }}</TD> --}}
                <TD style="text-align:right;">{{ $mitumoriKo->hiyo_kbn!=5?number_format($mitumoriKo->gaku):number_format(-$mitumoriKo->gaku) }}</TD>
                <TD style="overflow:hidden; max-width:80px;">{{ $mitumoriKo->biko }}</TD>
            </TR>
        @endforeach
        <TR>
            <TD>【合計】</TD>
            <TD></TD>
            <TD></TD>
            <TD></TD>
            <TD style="text-align:right;">{{number_format($sumGaku)}}</TD>
            <TD></TD>
        </TR>
        <TR>
            <TD>&nbsp;&nbsp;消費税（{{ $mitumori->tax_rate}}%）</TD>
            <TD></TD>
            <TD></TD>
            <TD></TD>
            <TD style="text-align:right;">{{number_format($sumGaku*$mitumori->tax_rate/100)}}</TD>
            <TD></TD>
        </TR>
        <TR>
            <TD>【総合計】</TD>
            <TD></TD>
            <TD></TD>
            <TD></TD>
            <TD style="text-align:right;">{{ number_format($sumGaku*(1+$mitumori->tax_rate/100)) }}</TD>
            <TD></TD>
        </TR>
        {{-- //項目の空の行を埋める --}}
        <?PHP
            if(($mitumoriKos->count()+3)<=23){
                $max=22;
                $start=$mitumoriKos->count()+3;
            }else{
                // 2ページ目以降の処理
                $twoPageDataCount=($mitumoriKos->count()+3)-23;
                $twoPageDataCountMod=$twoPageDataCount%39;//最大行数のあまり

                //forは0からカウントされるため２6ではなく２5がマックス、
                // だけど０の時はデータ数が０のためforは実行させない。
                $max=38;//最大行数―最後の合計、消費税、総合計の行の値
                
                $start=$twoPageDataCountMod;
            }
        ?>

        @if($start<>0)
        @for ($i=$start; $i <= $max; $i++)
            {{-- <TR @if($i==$max) style="page-break-after: always;" @endif> --}}
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
    
    
    
    <TABLE class="price" style="margin-top: 0; width:100%;">
        <thead>
            <TR>
                <Th colspan="6" style="font-weight:normal; height:50px; font-size:25px; letter-spacing: 5px; padding-bottom:5px;">内訳書</Th>
            </TR>
            <TR>
                <Th class="tableHeader" style="font-weight:normal; width:290px; max-width:290px;">品名</Th>
                <Th class="tableHeader" style="font-weight:normal; width:35px;">数量</Th>
                <Th class="tableHeader" style="font-weight:normal; width:35px;">単位</Th>
                <Th class="tableHeader" style="font-weight:normal; width:80px;">単価</Th>
                <Th class="tableHeader" style="font-weight:normal; width:90px;">金額</Th>
                <Th class="tableHeader" style="font-weight:normal; ">備考</Th>
            </TR>
        </thead>
        @foreach ($mitumoriSais as $key=>$mitumoriSai)
            {{-- 見積項目名 --}}
            <TR>
                <TD style="max-width:310px; overflow:hidden; font-size:10px; white-space: nowrap; ">{{ $mitumoriSai[0]->koGyoNo.'.'.$mitumoriSai[0]->koName }}</TD>
                <TD></TD>
                <TD></TD>
                <TD></TD>
                <TD></TD>
                <TD></TD>
            </TR>
            {{-- ここまで --}}

            {{-- 見積内訳 --}}
            <?PHP $sizaied_flg=0 ?>
            @foreach ($mitumoriSai as $key=>$m)
                {{-- 材料費合計 --}}
                @if($m->hiyo_kbn <> 0 && $m->hiyo_kbn <> 1 && $sizaied_flg==0 && $sumSizaiGaku[$mitumoriSai[0]->mitumoriKoId]<>0)
                    <TR>
                        <TD style="max-width:290px; overflow:hidden; font-size:10px; white-space: nowrap; ">【材料費合計】</TD>
                        <TD style="text-align:right;"></TD>
                        <TD style="text-align:center;"></TD>
                        <TD></TD>
                        <TD style="text-align:right;">{{number_format($sumSizaiGaku[$mitumoriSai[0]->mitumoriKoId])}}</TD>
                        <TD></TD>
                    </TR>
                    <?PHP $sizaied_flg=1 ?>
                @endif

                @if($m->su==0 && $m->tanka==0 && $m->tani=='')
                    <TR>
                        <TD style="font-size:10px; overflow:hidden; white-space: nowrap; height:22px;">{{ $m->name }}</TD>
                        <TD></TD><TD></TD><TD></TD><TD></TD><TD></TD>
                    </TR>
                    @continue
                @endif

                <TR>
                    <TD style="max-width:290px; max-height:17px; overflow:hidden; font-size:10px; white-space: nowrap; position: relative;">
                        {!! $m->nameSiyo !!}
                    </TD>
                    <TD style="text-align: right;">{{ $m->su }}</TD>
                    <TD style="text-align: center;">{{ $m->tani }}</TD>
                    <TD style="text-align: right;">{{ number_format($m->tanka) }}</TD>
                    <TD style="text-align: right;">{{ number_format($m->gaku) }}</TD>
                    <TD style="text-align: left; overflow:hidden; max-width:80px;">{{ $m->biko }}</TD>
                </TR>
            @endforeach

            {{-- 材料費合計 --}}
            @if($sizaied_flg==0 && $sumSizaiGaku[$mitumoriSai[0]->mitumoriKoId]<>0)
                <TR>
                    <TD style="max-width:290px; overflow:hidden; font-size:10px; white-space: nowrap; ">【材料費合計】</TD>
                    <TD style="text-align:right;"></TD>
                    <TD style="text-align:center;"></TD>
                    <TD></TD>
                    <TD style="text-align:right;">{{number_format($sumSizaiGaku[$mitumoriSai[0]->mitumoriKoId])}}</TD>
                    <TD></TD>
                </TR>
                <?PHP $sizaied_flg=1 ?>
            @endif

            {{-- ここまで --}}

            

            {{-- 労務費 --}}
            @if($mitumoriSai[0]->koRomGaku<>0)
                <TR>
                    <TD style="max-width:290px; overflow:hidden; font-size:10px; white-space: nowrap; ">労務費</TD>
                    <TD style="text-align:right;"></TD>
                    <TD style="text-align:center;"></TD>
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

                //材料費合計を入れる
                if($sumSizaiGaku[$mitumoriSai[0]->mitumoriKoId]<>0){
                    $etcRowCount+=1;
                }
                // data数＋項目名行＋労務費行＋合計行
                $mitumoriSaiCount = $mitumoriSai->count()+$etcRowCount;
                $start = $mitumoriSaiCount%37;
                $end = 36;

            ?>

            @if($start == 0) @continue @endif
            @for ($i=$start; $i <= $end; $i++)
            {{-- <TR @if($i==35) style="page-break-after: always;" @endif> --}}
            <TR>
                <TD style="max-height:17px!important;">&nbsp;</TD>
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