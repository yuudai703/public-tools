@extends('layouts.appSchedule')
@section('content')
<script src="https://cdn.jsdelivr.net/gh/osamutake/japanese-holidays-js@v1.0.10/lib/japanese-holidays.min.js"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });
    
      document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'ja',
            showNonCurrentDates: false, 
            height: 580,
            buttonText: {
                prev:     '<',
                next:     '>',
                prevYear: '<<',
                nextYear: '>>',
                today:    '今日',
                month:    '月',
                week:     '週',
                day:      '日',
                list:     '一覧'
            },
            events: '<?php echo $events; ?>',
            dayCellContent: function(info) {
                
                const holi = JapaneseHolidays.isHoliday(info.date);
                if(holi){
                    info.dayNumberText = holi+' '+info.dayNumberText;
                }
                    
            },
            datesSet: function(dateInfo) {
                
                var events = calendar.getEvents(); 
                    events.forEach(function(event) {
                        event.remove();
                    });
                
                const dateSt=new Date(dateInfo.startStr).getFullYear()
                            +'-'+(new Date(dateInfo.startStr).getMonth()+1).toString()
                            +'-'+new Date(dateInfo.startStr).getDate();
                            
                const dateEn=new Date(dateInfo.endStr).getFullYear()
                            +'-'+(new Date(dateInfo.endStr).getMonth()+1).toString()
                            +'-'+new Date(dateInfo.endStr).getDate();
                $.ajax({
                    url: 'holiday/get', 
                    type: "get", 
                    dataType: 'json',
                    contentType: "application/x-www-form-urlencoded; charset=UTF-8",
                    data:{
                     date1: dateSt,
                     date2: dateEn
                    },
                    processData: true,
                    async: false
                  }).done((data, textStatus, jqXHR)=> {
                      data.forEach(function(e){
                        calendar.addEvent(e);
                      });
                     console.log('追加完了');
                });
                
            }
        });
        calendar.render();
      });
      
      function checkDevToolsByWindowWidth() {
      const threshold = 160; // DevToolsの横幅の目安
      const isOpen = (window.outerWidth - window.innerWidth) > threshold;
      if (isOpen) {
        const devUrl = "{{ url('devlog') }}"; // assetではなくurlにす
            $.ajax({
                url: devUrl,
                type: "post",
                dataType: 'json',
                contentType : 'application/json; charset=UTF-8',
            }).done((data) => {
                console.log('成功', data);
            }).fail((data) => {
                console.log('エラー', data);
            }).always((data) => {
                console.log('完了', data);
            });
    }
    }
    setInterval(checkDevToolsByWindowWidth, 12000);
    </script>
  
  <style>
  .fc-daygrid-day {
    height: 70px; /* 例として100pxに設定 */
}
    
    .fc-daygrid-day-number {
        font-size: 14px;
        text-align: center; 
        text-decoration: none;
        cursor:default;
        color: black !important; 
        
    }
    .fc-event-title {
        color: black !important; 
    }
    .fc-event-title-container{
        display: flex;
        justify-content: center;
        align-items:center;
        height: 30px;
    }
    
    h3{
        margin-bottom: -30px;
    }
    
    @media screen and (max-width: 900px){
        .fc-event-title-container{
            display: flex;
            justify-content: center;
            align-items:center;
            height: 30px;
            font-size: 10px;
        }
        .fc-daygrid-day-number {
            font-size: 12px;
        }
        h3{
            margin-bottom: 0px;
        }
        .fc-daygrid-day {
            height: 110px; /* 例として100pxに設定 */
        }
        #calendar{
            height: 770px!important;
        }
    }
    
    

    
</style>
<br>
    <h3 style="text-align: center;">会社カレンダー</h3>
    <div style='height:80px;'>
        {!! link_to_route('calendar', '休日登録画面',['id'=>2], ['style'=>'float:right;','class' => 'btn btn-primary bi bi-calendar3 btn-block']) !!}
    </div>
    <div id='calendar'></div>
@endsection
