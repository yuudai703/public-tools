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
            showNonCurrentDates: false, // 現在の月以外の日付を非表示にする
            height: 720,
            width: 550,
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
                    info.dayNumberText = holi +' '+info.dayNumberText;
                    //info.el.insertAdjacentHTML("afterbegin",holi+'<br>'+info.dayNumberText);
                }
                    
            },
            dateClick: function(info) {
               // クリックされた日付にイベントを追加
               
              // クリックされた日付に既存のイベントがあれば削除
                  var events = calendar.getEvents(); 
                    events.forEach(function(event) {
                        if (event.start.toDateString() === info.date.toDateString()) {
                            event.remove();
                        }
                    });
                    
            
                let hcolor;
                let htitle;
                let kbn;
                 if(document.getElementById("flexRadioDefault1").checked==true){ //holidayカラー
                    hcolor = 'yellow';
                    htitle = '指定休日';
                    kbn = '1';
                 }else{
                    hcolor = 'aqua';
                    htitle = '法定休日';
                    kbn = '2';
                 }
                 
                   
                    calendar.addEvent({
                      title: htitle, // イベントのタイトル
                      start: info.dateStr, // イベントの開始日時（クリックされた日付）
                      allDay: true, // 終日のイベント
                      color: hcolor, //
                    });
                    
                    
                    $.ajax({
                        url: 'holiday', 
                        type: "post", 
                        dataType: 'json',
                        contentType: "application/x-www-form-urlencoded; charset=UTF-8",
                        data:{
                         date: info.dateStr,
                         title: kbn,
                        },
                        processData: true,
                        async: false
                      }).done((data, textStatus, jqXHR)=> {
                         console.log('登録完了');
                    });
            },
            eventClick: function(info) {
               
                const jpDate=info.event.start.getFullYear()
                            +'-'
                            +(info.event.start.getMonth()+1).toString()
                            +'-'
                            +info.event.start.getDate();
                            
                
                $.ajax({
                    url: 'holiday/delete', 
                    type: "delete", 
                    dataType: 'json',
                    contentType: "application/x-www-form-urlencoded; charset=UTF-8",
                    data:{
                     date: jpDate
                    },
                    processData: true,
                    async: false
                  }).done((data, textStatus, jqXHR)=> {
                     console.log('削除完了');
                     
                     info.event.remove();
                });
            },
            datesSet: function(dateInfo) {
                //画面遷移時イベントをすべて消す
                var events = calendar.getEvents(); 
                    events.forEach(function(event) {
                        event.remove();
                    });
              //カーボンで処理できるように
                const dateSt=new Date(dateInfo.startStr).getFullYear()
                            +'-'+(new Date(dateInfo.startStr).getMonth()+1).toString()
                            +'-'+new Date(dateInfo.startStr).getDate();
                            
                //カーボンで処理できるように
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
                     console.log('get完了');
                     
                });
            }
        });
        calendar.render();
      });
    </script>
  
  <style>
  .fc-daygrid-day {
    height: 100px; /* 例として100pxに設定 */
}
    
    .fc-daygrid-day-number {
        font-size: 15px;
        text-align: center; 
        text-decoration: none;
        cursor:default;
        color: black !important;
    }
    .fc-event-title {
        color: black !important; /* イベントのテキスト色を青に変更 */
    }
    .fc-event-title-container{
        display: flex;
        justify-content: center;
        align-items:center;
        height: 30px;
    }
</style>
<br>
    <h3 style="text-align: center;">会社カレンダー</h3>
<div>
    <p>※休日ラベルクリックで削除</p>
</div>
<div style="display: grid; justify-content: end; margin-right: 17%; margin-top: -5%;">
    <div class="form-check">
      <input class="form-check-input yellow" type="radio" name="flexRadioDefault" id="flexRadioDefault1" style="font-size: 20px;">
      <label class="form-check-label" for="flexRadioDefault1" style="font-size: 20px; background-color: yellow; border-radius: 8px; padding-left: 10px; padding-right: 10px;">
        指定休日
      </label>
    </div>
    <div class="form-check">
      <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" style="font-size: 20px;" checked>
      <label class="form-check-label" for="flexRadioDefault2" style="font-size: 20px; background-color: aqua; border-radius: 8px; padding-left: 10px; padding-right: 10px;">
        法定休日
      </label>
    </div>
</div>
<div style='height:50px;'>
    {!! link_to_route('calendar', '戻る',['id'=>1], ['style'=>'float:right;','class' => 'btn btn-primary bi bi-calendar3 btn-block']) !!}
</div>
<div id='calendar'></div>
@endsection