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
        

        
         @page {
              size: B4 landscape;
              margin: 6mm 0mm 3mm 0mm;
            }
          
       
        body{
            font-size:10px;
            -webkit-print-color-adjust: exact;
        }
        table{
            width: 600px!important;
            margin:auto!important;
        }
        th{
            text-align:center;
            background-color: gray;
        }
        th,td{
            border:1px solid black!important;
            padding:1px!important;
            max-width:29px!important;
        }
        
        .day{
            height:50px!important;
        }
        
        .holiday{
            background-color:#DDDDDD!important;
        }
        
        @media print {
            
        th,td{
            border:1px solid black!important;
            padding:1px!important;
            min-width:29px!important;
        }
           table{
            width:  96%!important;
            margin:auto!important;
        }
        .event{
            font-size:8px!important;
        }
        
        .holiday{
            background-color:#DDDDDD!important;
        }
        
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
        
    <?php
        $colspan=$endDay-7;
    ?>
    
    
    
    <table class='table'>
        <thead>
            <tr>
                <td colspan="4"style="border:none!important;" nowrap>{{$ym->format('Y年m月')}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$depaName}}</td>
                <td colspan="{{$colspan}}" style="border:none!important; text-align:center;">スケジュール表</td>
                <td colspan="4"style="border:none!important; text-align:right;">印刷日:{{carbon\carbon::now()->format('Y/m/d')}}</td>
            <tr>
            <tr>
                <td class='table-top' style="background-color:#BBBBBB!important; text-align:center!important; padding-top:5px!important;">日</td>
                @for($i=1; $i<=$endDay; $i++)
                    <td class="day-top" style="@if(in_array($i,$holis)) background-color:#888888!important; @else background-color:#BBBBBB!important;@endif text-align:center;">{{$i}}<br>({{$dayOfWeek[($i+$weekNumber-1)%7]}})</td>
                @endfor
            </tr>
        </thead>
        <tbody class='selectable'>
        @foreach($users as $user)
        <tr class="userRow">
            <td class="userName disable-selection non-selectable" style="background-color:#BBBBBB!important; width:40px!important; padding:3px!important;">{{$user["name"]}}</td>
            <!--アイホン使用時グラフィック下に行く原因-->
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-01'}} day sortable connectedSortable" @if(in_array(1,$holis))  style="background-color:#DDDDDD!important;" @endif  ></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-02'}} day sortable connectedSortable" @if(in_array(2,$holis))  style="background-color:#DDDDDD!important;" @endif  ></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-03'}} day sortable connectedSortable" @if(in_array(3,$holis))  style="background-color:#DDDDDD!important;" @endif  ></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-04'}} day sortable connectedSortable" @if(in_array(4,$holis))  style="background-color:#DDDDDD!important;" @endif  ></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-05'}} day sortable connectedSortable" @if(in_array(5,$holis))  style="background-color:#DDDDDD!important;" @endif  ></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-06'}} day sortable connectedSortable" @if(in_array(6,$holis))  style="background-color:#DDDDDD!important;" @endif  ></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-07'}} day sortable connectedSortable" @if(in_array(7,$holis))  style="background-color:#DDDDDD!important;" @endif  ></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-08'}} day sortable connectedSortable" @if(in_array(8,$holis))  style="background-color:#DDDDDD!important;" @endif  ></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-09'}} day sortable connectedSortable" @if(in_array(9,$holis))  style="background-color:#DDDDDD!important;" @endif  ></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-10'}} day sortable connectedSortable" @if(in_array(10,$holis)) style="background-color:#DDDDDD!important;"  @endif ></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-11'}} day sortable connectedSortable" @if(in_array(11,$holis)) style="background-color:#DDDDDD!important;"  @endif ></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-12'}} day sortable connectedSortable" @if(in_array(12,$holis)) style="background-color:#DDDDDD!important;"  @endif ></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-13'}} day sortable connectedSortable" @if(in_array(13,$holis)) style="background-color:#DDDDDD!important;"  @endif ></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-14'}} day sortable connectedSortable" @if(in_array(14,$holis)) style="background-color:#DDDDDD!important;"  @endif ></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-15'}} day sortable connectedSortable" @if(in_array(15,$holis)) style="background-color:#DDDDDD!important;"  @endif ></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-16'}} day sortable connectedSortable" @if(in_array(16,$holis)) style="background-color:#DDDDDD!important;"  @endif ></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-17'}} day sortable connectedSortable" @if(in_array(17,$holis)) style="background-color:#DDDDDD!important;"  @endif ></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-18'}} day sortable connectedSortable" @if(in_array(18,$holis)) style="background-color:#DDDDDD!important;"  @endif ></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-19'}} day sortable connectedSortable" @if(in_array(19,$holis)) style="background-color:#DDDDDD!important;"  @endif ></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-20'}} day sortable connectedSortable" @if(in_array(20,$holis)) style="background-color:#DDDDDD!important;"  @endif ></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-21'}} day sortable connectedSortable" @if(in_array(21,$holis)) style="background-color:#DDDDDD!important;"  @endif ></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-22'}} day sortable connectedSortable" @if(in_array(22,$holis)) style="background-color:#DDDDDD!important;"  @endif ></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-23'}} day sortable connectedSortable" @if(in_array(23,$holis)) style="background-color:#DDDDDD!important;"  @endif ></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-24'}} day sortable connectedSortable" @if(in_array(24,$holis)) style="background-color:#DDDDDD!important;"  @endif ></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-25'}} day sortable connectedSortable" @if(in_array(25,$holis)) style="background-color:#DDDDDD!important;"  @endif ></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-26'}} day sortable connectedSortable" @if(in_array(26,$holis)) style="background-color:#DDDDDD!important;"  @endif ></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-27'}} day sortable connectedSortable" @if(in_array(27,$holis)) style="background-color:#DDDDDD!important;"  @endif ></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-28'}} day sortable connectedSortable" @if(in_array(28,$holis)) style="background-color:#DDDDDD!important;" @endif></td>
                
                @if(isset($user["day29"]))
                    <td class="{{'event-'.$user['id'].'-'.$dateYM.'-29'}} day sortable connectedSortable" @if(in_array(29,$holis)) style="background-color:#DDDDDD!important;" @endif></td>
                @endif
                
                @if(isset($user["day30"]))
                    <td class="{{'event-'.$user['id'].'-'.$dateYM.'-30'}} day sortable connectedSortable" @if(in_array(30,$holis)) style="background-color:#DDDDDD!important;" @endif></td>
                @endif
                
                @if(isset($user["day31"]))
                    <td class="{{'event-'.$user['id'].'-'.$dateYM.'-31'}} day sortable connectedSortable" @if(in_array(31,$holis)) style="background-color:#DDDDDD
                    !important;" @endif></td>
                @endif
        </tr>
        @endforeach
        </tbody>
    </table>
    
    
    <script>
        
        //最初に実行される
    window.onload = async function() {
        
        //イベントセット
        (await function () {
            const leave_events = <?PHP echo $leave_requests ?>;
            for(let obj of leave_events ) {
                const target=document.querySelector(obj.event);
                if(target){
                    let colorName;
                    switch (obj.name) {
                        case '振替休日': 
                            colorName='blue';
                            target.innerHTML+="<div class='event notMove' value='"+obj.id+"' style='color:"+colorName+"; border:none; font-weight: bold; text-align:center;'  data-bs-toggle='tooltip' onContextmenu='contextmenuAddEvent(this)'>"+obj.name+"</div>";
                            break;
                            
                        case '有給休暇':
                            colorName='#FF4F02';
                            target.innerHTML+="<div class='event notMove' value='"+obj.id+"' style='color:"+colorName+"; border:none; font-weight: bold; text-align:center;'  data-bs-toggle='tooltip' onContextmenu='contextmenuAddEvent(this)'>"+obj.name+"</div>";
                            break;
                        default:
                            colorName='black';
                            target.innerHTML+="<div class='event notMove' value='"+obj.id+"' style='color:"+colorName+"; border:none; font-weight: bold; text-align:center;' data-bs-toggle='tooltip' data-bs-title='削除更新は勤怠画面から'>"+obj.name+"</div>";
                            break;
                    }
                }
            }
        }());
        
        
        //イベントセット
        (await function () {
            const user_events = <?PHP echo $events ?>;
            for(let obj of user_events ) {
                const target=document.querySelector(obj.event);
                if(target){
                    target.innerHTML+="<div class='event' style='border:1px dotted black; margin-bottom:1px!important;'>"+obj.name+"</div>";
                }
            }
        }());
        
    window.print();
        
    }
      
    </script>
    
    </body>
</html>