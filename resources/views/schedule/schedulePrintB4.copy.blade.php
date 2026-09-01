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
              margin: 3mm;
            }
          
       
        body{
            font-size:10px;
            -webkit-print-color-adjust: exact;
        }
        table{
            width:  395px!important;
            margin:auto!important;
        }
        th{
            text-align:center;
            background-color: gray;
        }
        th,td{
            border:1px solid black!important;
            padding:1px!important;
            min-width:33px!important;
        }
        
        @media print {
           table{
            width:  100%!important;
            margin:auto!important;
        }
        .event{
            font-size:9px!important;
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
        $colspan=$endDay-5;
    ?>
    
    
    
    <table class='table'>
        <thead>
            <tr>
                <td colspan="3"style="border:none!important;">{{$ym->format('Y年m月')}}</td>
                <td colspan="{{$colspan}}" style="border:none!important; text-align:center;">スケジュール表</td>
                <td colspan="3"style="border:none!important;">印刷日:{{carbon\carbon::now()->format('Y/m/d')}}</td>
            <tr>
            <tr>
                <td class='table-top' style="background-color:#DDDDDD!important;">日</td>
                @for($i=1; $i<=$endDay; $i++)
                    <td class="day-top @if(in_array($i,$holis)) holidayTop @endif" style="background-color:#DDDDDD!important; text-align:center;">{{$i}}<br>({{$dayOfWeek[($i+$weekNumber-1)%7]}})</td>
                @endfor
            </tr>
        </thead>
        <tbody class='selectable'>
        @foreach($users as $user)
        <tr class="userRow {{$depaArray[$user['id']]}} @if(strstr($depaArray[$user['id']],(string)$selectdepa)==false && $selectdepa!="all") d-none @endif">
            <td class="userName disable-selection non-selectable" @if(strpos($user['id'],"out")===0) onClick="outUserEdit('<?PHP echo $user["name"] ?>','<?PHP echo $depaArray[$user["id"]] ?>','<?PHP echo $user["id"] ?>')" @endif style="background-color:#DDDDDD!important;"　>{{$user["name"]}}</td>
            <!--アイホン使用時グラフィック下に行く原因-->
           
                
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-01'}} day sortable connectedSortable @if(in_array(1,$holis)) holiday @endif  @if(1==$today) todayUser @endif"></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-02'}} day sortable connectedSortable @if(in_array(2,$holis)) holiday @endif  @if(2==$today) todayUser @endif"></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-03'}} day sortable connectedSortable @if(in_array(3,$holis)) holiday @endif  @if(3==$today) todayUser @endif"></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-04'}} day sortable connectedSortable @if(in_array(4,$holis)) holiday @endif  @if(4==$today) todayUser @endif"></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-05'}} day sortable connectedSortable @if(in_array(5,$holis)) holiday @endif  @if(5==$today) todayUser @endif"></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-06'}} day sortable connectedSortable @if(in_array(6,$holis)) holiday @endif  @if(6==$today) todayUser @endif"></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-07'}} day sortable connectedSortable @if(in_array(7,$holis)) holiday @endif  @if(7==$today) todayUser @endif"></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-08'}} day sortable connectedSortable @if(in_array(8,$holis)) holiday @endif  @if(8==$today) todayUser @endif"></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-09'}} day sortable connectedSortable @if(in_array(9,$holis)) holiday @endif  @if(9==$today) todayUser @endif"></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-10'}} day sortable connectedSortable @if(in_array(10,$holis)) holiday @endif @if(10==$today) todayUser @endif"></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-11'}} day sortable connectedSortable @if(in_array(11,$holis)) holiday @endif @if(11==$today) todayUser @endif"></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-12'}} day sortable connectedSortable @if(in_array(12,$holis)) holiday @endif @if(12==$today) todayUser @endif"></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-13'}} day sortable connectedSortable @if(in_array(13,$holis)) holiday @endif @if(13==$today) todayUser @endif"></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-14'}} day sortable connectedSortable @if(in_array(14,$holis)) holiday @endif @if(14==$today) todayUser @endif"></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-15'}} day sortable connectedSortable @if(in_array(15,$holis)) holiday @endif @if(15==$today) todayUser @endif"></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-16'}} day sortable connectedSortable @if(in_array(16,$holis)) holiday @endif @if(16==$today) todayUser @endif"></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-17'}} day sortable connectedSortable @if(in_array(17,$holis)) holiday @endif @if(17==$today) todayUser @endif"></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-18'}} day sortable connectedSortable @if(in_array(18,$holis)) holiday @endif @if(18==$today) todayUser @endif"></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-19'}} day sortable connectedSortable @if(in_array(19,$holis)) holiday @endif @if(19==$today) todayUser @endif"></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-20'}} day sortable connectedSortable @if(in_array(20,$holis)) holiday @endif @if(20==$today) todayUser @endif"></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-21'}} day sortable connectedSortable @if(in_array(21,$holis)) holiday @endif @if(21==$today) todayUser @endif"></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-22'}} day sortable connectedSortable @if(in_array(22,$holis)) holiday @endif @if(22==$today) todayUser @endif"></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-23'}} day sortable connectedSortable @if(in_array(23,$holis)) holiday @endif @if(23==$today) todayUser @endif"></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-24'}} day sortable connectedSortable @if(in_array(24,$holis)) holiday @endif @if(24==$today) todayUser @endif"></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-25'}} day sortable connectedSortable @if(in_array(25,$holis)) holiday @endif @if(25==$today) todayUser @endif"></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-26'}} day sortable connectedSortable @if(in_array(26,$holis)) holiday @endif @if(26==$today) todayUser @endif"></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-27'}} day sortable connectedSortable @if(in_array(27,$holis)) holiday @endif @if(27==$today) todayUser @endif"></td>
                <td class="{{'event-'.$user['id'].'-'.$dateYM.'-28'}} day sortable connectedSortable @if(in_array(28,$holis)) holiday @endif @if(28==$today) todayUser @endif"></td>
                
                @if(isset($user["day29"]))
                    <td class="{{'event-'.$user['id'].'-'.$dateYM.'-29'}} day sortable connectedSortable @if(in_array(29,$holis)) holiday @endif @if(29==$today) todayUser @endif"></td>
                @endif
                
                @if(isset($user["day30"]))
                    <td class="{{'event-'.$user['id'].'-'.$dateYM.'-30'}} day sortable connectedSortable @if(in_array(30,$holis)) holiday @endif @if(30==$today) todayUser @endif"></td>
                @endif
                
                @if(isset($user["day31"]))
                    <td class="{{'event-'.$user['id'].'-'.$dateYM.'-31'}} day sortable connectedSortable @if(in_array(31,$holis)) holiday @endif @if(31==$today) todayUser @endif"></td>
                @endif
                
            <!-- -->
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
                            target.innerHTML+="<div class='event notMove' value='"+obj.id+"' style='color:"+colorName+"; border:none; font-weight: bold'  data-bs-toggle='tooltip' onContextmenu='contextmenuAddEvent(this)'>"+obj.name+"</div>";
                            break;
                            
                        case '有給休暇':
                            colorName='#FF4F02';
                            target.innerHTML+="<div class='event notMove' value='"+obj.id+"' style='color:"+colorName+"; border:none; font-weight: bold'  data-bs-toggle='tooltip' onContextmenu='contextmenuAddEvent(this)'>"+obj.name+"</div>";
                            break;
                        default:
                            colorName='black';
                            target.innerHTML+="<div class='event notMove' value='"+obj.id+"' style='color:"+colorName+"; border:none; font-weight: bold' data-bs-toggle='tooltip' data-bs-title='削除更新は勤怠画面から'>"+obj.name+"</div>";
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