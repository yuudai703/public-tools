@extends('layouts.appKintai')
@section('content')

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary modaleOpen d-none" data-bs-toggle="modal" data-bs-target="#exampleModal">
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="">
  <div class="modal-dialog"style="pointer-events: none;">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <select id='anken'>
            <option value="現場1">現場1</option>
            <option value="現場2">現場2</option>
            <option value="現場3">現場3</option>
        </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id='closeBtn' data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id='submitBtn'>Save changes</button>
      </div>
    </div>
  </div>
</div>



<br/>

<div id='dp'>
</div>

<!--<script src="{{ asset('/js/daypilot-modal.min.js') }}"></script>-->
<script src="{{ asset('/js/daypilot-all.min.js') }}"></script>

<script>
    
    
    
    
//let eventkbn = false;

 const dp = new DayPilot.Scheduler("dp", {
    locale: "ja-jp",
    startDate: "2024-01-01",
    days: 365,
    scale: "Day",
    timeHeaders: [
        {groupBy: "Month", format: "yyyy年 MM月"},
        {groupBy: "Day", format: "d ddd"}
    ],
    treeEnabled: true,
    treePreventParentUsage: true,
    heightSpec: "Max",
    height: 500,
    cellWidth: 100,
    eventMovingStartEndEnabled: true,
    eventResizingStartEndEnabled: true,
    timeRangeSelectingStartEndEnabled: true,
    
    contextMenu: new DayPilot.Menu({
        items: [
            {
                text: "編集",
                onClick: (args) => {
                    dp.events.edit(args.source);
                }
            },
            {
                text: "削除",
                onClick: (args) => {
                    dp.events.remove(args.source);
                }
            },
            {text: "-"},
            {
                text: "選択",
                onClick: (args) => {
                    dp.multiselect.add(args.source);
                }
            },
        ]
    }),
    bubble: new DayPilot.Bubble({
        onLoad: (args) => {
            const e = args.source;
            const text = DayPilot.Util.escapeHtml(e.text());
            const start = e.start().toString("M/d/yyyy h:mm tt");
            const end = e.end().toString("M/d/yyyy h:mm tt");
            args.html = `<div><b>${text}</b></div><div>Start: ${start}</div><div>End: ${end}</div>`;
        }
    }),
    onEventMoved: (args) => {
        const text = args.e.text();
        dp.message(`(${text})のイベントを移動しました。`);
        console.log(args);
    },
    onEventMoving: (args) => {
        //see more examples at https:doc.daypilot.org/scheduler/event-moving-customization/
        if (args.e.resource() === "A" && args.resource === "B") {  // don't allow moving from A to B
            args.left.enabled = false;
            args.right.html = "You can't move an event from Room 1 to Room 2";

            args.allowed = false;
        }
        else if (args.resource === "B") {  // must start on a working day, maximum length one day
            while (args.start.getDayOfWeek() === 0 || args.start.getDayOfWeek() === 6) {
                args.start = args.start.addDays(1);
            }
            args.end = args.start.addDays(1);  // fixed duration
            args.left.enabled = false;
            args.right.html = "Events in Room 2 must start on a workday and are limited to 1 day.";
        }
        
        
        
    },
    onEventResized: (args) => {
        
        dp.message("Resized: " + args.e.text());
    },
    onTimeRangeSelected: async (args) => {
    
        document.querySelector('.modaleOpen').click();
        const submitBtn = document.querySelector('#submitBtn');
        const closeBtn = document.querySelector('#closeBtn');
        const topBtnClose = document.querySelector('.btn-close');//上の×
        const shadowMask = document.querySelector('#exampleModal');
        
        
        //console.log(submitBtn.)
        
        //submitBtn.addEventListener('click',function(e){
        //    console.log(args.start);
        //    dp.events.add({
        //        start: args.start,
        //        end: args.end,
        //        id: DayPilot.guid(),
        //        resource: args.resource,
        //        text: 'test'
        //    });
        //    
        //    console.log(e.currentTarget);
        //    
        //    var target = e.currentTarget;
        //    //btnElに指定したイベントをremove
        //submitBtn.removeEventListener('click', arguments.callee, false);
        //    });
        
        
        //document.getElementById('submitBtn').removeEventListener('click', handleSubmit);
        
        function handleSubmit(e) {
                console.log(args.resource,args);
                
                const selectedValue=document.getElementById('anken').value;
                
                
                
                //インサート
                $.ajax({
                    url: '/scheduleDayPailot/insert', 
                    type: "post", 
                    dataType: 'json',
                    contentType: "application/x-www-form-urlencoded; charset=UTF-8",
                    data:{
                        start: args.start,//開始
                        end: args.end,//終了
                        resource: args.resource,//user_id
                        text: selectedValue//選択案件
                    },
                    processData: true,
                }).done((data, textStatus, jqXHR)=> {
                    console.log('登録完了');
                     
                    dp.events.add({
                        start: args.start,
                        end: args.end,
                        id: DayPilot.guid(),
                        resource: args.resource,
                        text: selectedValue
                    });
                });
                
                
                
            
                var target = e.currentTarget;
                // btnElに指定したイベントをremove
                
                //eventkbn=true;
                document.getElementById('submitBtn').removeEventListener('click', handleSubmit);
                
                closeBtn.click();
            }

        // イベントリスナーの登録
        submitBtn.addEventListener('click', handleSubmit);
    
    
        closeBtn.addEventListener('click',function(e){
            dp.clearSelection();
            document.getElementById('submitBtn').removeEventListener('click', handleSubmit);
        });
        topBtnClose.addEventListener('click',function(e){
            dp.clearSelection();
            document.getElementById('submitBtn').removeEventListener('click', handleSubmit);
        });
        
        
        shadowMask.addEventListener('click',function(e){
            if(event.target.closest('.modal-content') === null) {
                dp.clearSelection();
                document.getElementById('submitBtn').removeEventListener('click', handleSubmit);
            }
        });
        
        
        
        
        
            
            
        
        
        //submitBtn.removeEventListener('click',submitE);
    
    
    
    
    //console.log(args);
    
        
            
            
            //modal.onHtmlRendered = function() {
            //    console.log(document.getElementById('save'));
            //    document.getElementById('save').addEventListener('click',function(){
            //        //dp.events.add({
            //        //    start: args.start,
            //        //    end: args.end,
            //        //    id: DayPilot.guid(),
            //        //    resource: args.resource,
            //        //    text: name
            //        //});
            //    });
            //    document.querySelector('#cancel').onClick= function(){
            //    }
            //}
    
    
    
    
        //const modal = await DayPilot.Modal.prompt("New event name:", "New Event");
        //dp.clearSelection();
        //if (modal.canceled) {
        //    return;
        //}
        //
        //
        //const name = modal.result;
        //console.log(args.start,DayPilot.guid(),args.resource);
        //dp.events.add({
        //    start: args.start,
        //    end: args.end,
        //    id: DayPilot.guid(),
        //    resource: args.resource,
        //    text: name
        //});
        
        
        
        //dp.message("新規作成しました。");
    },
    onEventMove: (args) => {
        if (args.ctrl) {
            dp.events.add({
                start: args.newStart,
                end: args.newEnd,
                text: "Copy of " + args.e.text(),
                resource: args.newResource,
                id: DayPilot.guid()  // generate random id
            });

            // notify the server about the action here
            args.preventDefault(); // prevent the default action - moving event to the new location
        }
    },
    onEventClick: (args) => {
        DayPilot.Modal.alert(args.e.data.text);
    },
    separators: [
      {location: new DayPilot.Date("2024-09-01"), color: "red", toolTip: "Test"},
    ],
});


dp.init();
dp.scrollTo("2024-09-01");

const users = <?php echo $users ?>;

const app = {
    barColor(i) {
        const colors = ["#3c78d8", "#6aa84f", "#f1c232", "#cc0000"];
        return colors[i % 4];
    },
    barBackColor(i) {
        const colors = ["#a4c2f4", "#b6d7a8", "#ffe599", "#ea9999"];
        return colors[i % 4];
    },
    loadData() {
        const resources = users;

        const events = [];
        for (let i = 0; i < 12; i++) {
            const duration = Math.floor(Math.random() * 6) + 1; // 1 to 6
            const durationDays = Math.floor(Math.random() * 6) + 1; // 1 to 6
            const start = Math.floor(Math.random() * 6) + 2; // 2 to 7
            const e = {
                start: new DayPilot.Date("2024-09-05T12:00:00").addDays(start),
                end: new DayPilot.Date("2024-09-05T12:00:00").addDays(start).addDays(durationDays).addHours(duration),
                id: i + 1,
                resource: 190,//ユーザーIDらしい
                text: "Event " + (i + 1),//イベントの内容
                bubbleHtml: "Event " + (i + 1),//なぞ
                barColor: app.barColor(i),
                barBackColor: app.barBackColor(i),
            };
            events.push(e);
        }
        dp.update({resources, events});
    },
};

app.loadData();
document.querySelector('.scheduler_default_corner').children[1].remove();
    
</script>




@endsection