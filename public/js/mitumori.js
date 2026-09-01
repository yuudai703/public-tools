//const { forEach } = require("lodash");

console.log('aa');

tippy('#addKikakuBtn', {
   content: '資材を選択後、現在開いている見積のみ表示される規格を追加します。',
   followCursor: true,
});

//列ごとfocusする
for (let i = 0; i <= 10; i++) {
    $(document).on('keydown', '.input' + i, function(event) {
        if (event.key !== 'Enter') {
            return;
        }
        const $cells = $('.input' + i);
        const key = $cells.index(this);
        if ($cells.eq(key + 1).length === 1) {
            console.log($cells.eq(key + 1));
            $cells.eq(key + 1).focus();
        } else {
            console.log('ok');
            $cells.eq(0).focus();
        }
    });

}

let temp2 = "";
$(document).on("mousedown", "input[list]", function () {
    temp2 = $(this).val();
    $(this).val("");
});
$(document).on("click", "input[list]", function () {
    $(this).val(temp2);
    temp2 = "";
});




// ajax demo
// const miId="{{ $mitumori->id }}";
let sizaiCode; //資材追加用
$('#ajax').jstree({
		'core' : {
			'data' : {
				"url" : "./add/root.json",
				"dataType" : "json", // needed only if you do not supply JSON headers
                "data" : function (node) {
                    return { "id" : node.id };
				},
                "error": function(xhr) {
                    if (xhr.status === 419) {
                        alert('セッションが切れました。ページを再読み込みします。');
                        location.reload();
                    }
                }
			}
		},
         
        
	})
    .on('click', '.jstree-anchor',function(e){
        if(this.ariaLevel==3 && openCheck == false){
                openCheck = true;
                sizaiKikakuGet(this.id);
                // sizaiH.innerHTML = this.text;
                sizaiCode = this.id;
        }
    });


function sizaiKikakuGet(id){
        $.ajax({
            type : 'get',
            url : './add/root.json?id='+id,
            dataType : 'json',
            data: {mitumoriId: mitumoriId},
            contentType : 'application/json; charset=UTF-8',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
            // error: function(xhr) {
            //     if (xhr.status === 419) {
            //         alert('セッションが切れました。ページを再読み込みします。');
            //         location.reload();
            //     }
            // }
        }).done(function(response){

            const data = response.data;
            const sekoNames = response.sekoNames;
            const sekoNameArray = Object.values(sekoNames);
            const sekoCodes = JSON.parse(response.sekoCodes);
            const sekoCodes2 = JSON.parse(response.sekoCodes2);
            const keepSizais = response.keepSizais;
            const bugakariArray = response.bugakariArray;
            const bugakariEditArray = response.bugakariEditArray;
            const kikakuHeader = response.kikakuHeader;
            const kikakuHeader2 = response.kikakuHeader2;
            const sizai_name = response.sizai_name;

            modalTable.innerHTML = "";
            
            const eleB=document.createElement("tbody");
            
            const eleTrHeader=document.createElement("tr");
            
            
            document.querySelector('#sizaiCode').value = sizai_name.code;

            let is_hidden1;
            let is_hidden2;
            if(document.querySelector("#bordered-radio-1").checked==true){
                is_hidden1="";
                is_hidden2="hidden";
            }else{
                is_hidden1="hidden";
                is_hidden2="";
            }

            //拾いヘッダー
            kikakuHeader.forEach(name => {
                const hleft = name=="規格"? "left:0px; z-index:10;" : "";
                eleTrHeader.innerHTML += `<td style='text-align:center; position: sticky; top:0px; ${hleft}' class='${is_hidden1} bg-gray-300 border border-gray-400 '>`+name+`</td>`;
            });
            
            //編集header
            let sekoIndex=0;
            kikakuHeader2.forEach(name => {
                const targetColumn="seko"+sekoIndex;
                if(sekoIndex==0){
                    eleTrHeader.innerHTML +=`<td style="width:80px; text-align:center; position: sticky; top:0px; left:0px; z-index:10;" class="${is_hidden2} bg-gray-300 border border-gray-400">${name}</td>`;
                }else if(sekoIndex==11){
                    eleTrHeader.innerHTML +=`<td style="width:80px; text-align:center; position: sticky; top:0px;" class="${is_hidden2} bg-gray-300 border border-gray-400">普通</td>`;    
                }else if(sekoIndex==12){
                    eleTrHeader.innerHTML +=`<td style="width:80px; text-align:center; position: sticky; top:0px;" class="${is_hidden2} bg-gray-300 border border-gray-400">特殊</td>`;
                }else{
                    
                    eleTrHeader.innerHTML +=`<td style='max-width:80px; text-align:center; position: sticky; top:0px;' class='${is_hidden2} bg-gray-300 border border-gray-400'>
                                                <select class="h-6 p-0 text-center text-sm" onchange="sizaiEditAjax('sizai_names','${sizai_name.id}','${targetColumn}',this.value)">`+
                                                       sekoNameArray.map(sekoName => {
                                                            return `<option value="${sekoName.code}" ${sekoName.name==name?'selected':''}>${sekoName.name}</option>`;
                                                       })
                                                +`</select>    
                                            </td>`;
                }
                sekoIndex++;
            });

            eleB.appendChild(eleTrHeader);

            //規格データ
            data.forEach(element => {
                const eleTr=document.createElement("tr");

                let bgColor="";
                let deleteBtn="";
                if(element.mitumoriId!=null){
                    bgColor="bg-green-100 -ml-5";
                    deleteBtn=`<button type='button' onclick="deleteKikaku('${element.id}')"  class="bg-[#ffa1a1] m-0 mr-0 hover:bg-blue-700 deleteBtn">削除</button>`;
                } 
                
                //規格名
                eleTr.innerHTML += `<td nowrap class='border border-gray-400 p-0 w-auto flex min-w-[200px]' style='position: sticky; left:0px;'>
                                        ${deleteBtn}
                                        <input type='text' value='`+element.name+`' onkeydown="keyDown()" placeholder="規格名無" onchange="sizaiEditAjax('kikaku_name_for_mitumoris','${element.id}','name',this.value)" class='border placeholder:text-[15px]  border-gray-400 m-0 text-sm overflow-visible hiroiInputColor w-full h-full px-1 py-0 ${bgColor} -ml-[10px] border-none'>
                                    </td>`;
                //施工データ
                Object.keys(sekoCodes).forEach(key => {
                    let su;
                    if(keepSizais[element.id] == undefined|| keepSizais[element.id][sekoCodes[key].code] == undefined){
                        su = "";
                    }else{
                        su=keepSizais[element.id][sekoCodes[key].code]['su'];
                    }
                    
                    eleTr.innerHTML += `
                                        <td class='border border-gray-400 ${is_hidden1}'>
                                            <input  placeholder="${bugakariArray[element.id][sekoCodes[key].code]}" onkeydown="keyDown()" onchange="sizaiSuAjax(this.value,${element.id},'${sekoCodes[key].code}','${sekoCodes[key].bugakariColumn}')" value="${su}" name='su[]' class='hiroiInputColor ${bgColor}' type='number' style='padding:0px 3px; width:80px; text-align:right; border-style:none; '>
                                        </td>
                                            `;
                });

                

                bugakariEditArray[element.id].forEach(bugakari => {
                    eleTr.innerHTML += 
                                        `
                                        <td class='border border-gray-400 ${is_hidden2}'>
                                            <input value="${bugakari.v}"  onchange="sizaiEditAjax('sizai_kikakus','${element.id}','${bugakari.c}',this.value)" class='hiroiInputColor ${bgColor}' type='text' min='0' style='padding:0px 3px; width:80px; text-align:right; border-style:none;'>
                                        </td>
                                            `;
                });
                
                eleB.appendChild(eleTr);
            });

           

            modalTable.appendChild(eleB);
            openCheck = false;
            addHiroiColor();//layout app method
            
            
        }).fail(function(data){
            /* 通信失敗時 */
            console.log('残念');
        });
}

function keyDown(){
    // console.log(event.target);
    if(event.key === 'Enter'){
        event.target.blur(); // フォーカスを外すことでchangeイベントを発火させる
        // event.preventDefault(); // デフォルトのEnterキーの動作を無効化
    }
}

//拾う　歩掛編集の切り替え時にtreeデータを再クリック
document.querySelectorAll("#bordered-radio-1,#bordered-radio-2")
.forEach(function(e){
    e.addEventListener("change",function(){
        sizaiKikakuGet(sizaiCode);
    })
});

function sizaiSuAjax(value, kikakuId, sekoCode, bugakariColumn){
    const sekoSyu=document.querySelector('#sekoSyu').value;
    const sagyoSyu=document.querySelector('#sagyoSyu').value;
    $.ajax({
        type : 'post',
        url : './keep/sizai',
        dataType : 'json',
        contentType : 'application/json; charset=UTF-8',
        data: JSON.stringify({
            su: value,
            kikakuId: kikakuId,
            sekoCode: sekoCode,
            bugakariColumn: bugakariColumn,
            sekoSyu: sekoSyu,
            sagyoSyu: sagyoSyu,
            mitumoriId: mitumoriId,
            mitumoriKoId: mitumoriKoId 
        }),
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    }).done(function(response){

        const hiroiTable=document.querySelector("#hiroiTable");

        // 既にあるかチェック
        const existenceCheck=document.querySelectorAll(
            '[data-kikaku-id="' + response.kikakuId + '"][data-seko-code="' + response.sekoCode + '"]'
        );


        if(existenceCheck == undefined && response.su!=0 || existenceCheck.length == 0  && response.su!=0){
            const tr=document.createElement("tr");
            tr.dataset.kikakuId=response.kikakuId;
            tr.dataset.sekoCode=response.sekoCode;
                tr.innerHTML = `<td class="pl-2 overflow-hidden  text-xs  h-6">
                                    <span style="background-color:blue; padding:1px 4px 1px 6px; text-align:center;" class="text-white text-center text-xs pr-[4px] py-[1px] pl-[6px] rounded-xl">
                                        ${response.su}
                                    </span>    
                                    ${response.sizaiName}${response.kikakuName}${response.sekoName}
                                </td>`;

            hiroiTable.appendChild(tr);
        }else if(response.su>0){
            //すでに存在しているなら
            existenceCheck[0].innerHTML = `<td class="pl-2 overflow-hidden  text-xs  h-6">
                                                <span style="background-color:blue; padding:1px 4px 1px 6px; text-align:center;" class="text-white text-center text-xs pr-[4px] py-[1px] pl-[6px] rounded-xl">
                                                    ${response.su}
                                                </span>    
                                                ${response.sizaiName}${response.kikakuName}${response.sekoName}
                                            </td>`;
        }else if(response.su==0){
            //0なら削除
            existenceCheck[0].remove();
        }


    }).fail(function(data){
        /* 通信失敗時 */
        console.log('残念');
    });
}

function sizaiEditAjax(table,id,bugakariColumn,value){
    $.ajax({
        type : 'post',
        url : './edit/sizai',
        dataType : 'json',
        contentType : 'application/json; charset=UTF-8',
        data: JSON.stringify({
            table: table,
            id: id,
            v: value,
            bugakariColumn: bugakariColumn
        }),
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    }).done(function(response){
        
    }).fail(function(data){
        /* 通信失敗時 */
        console.log('残念');
    });
}

//memoと備考の切り替え
function dragOrGyoText(selectTaget){
    document.querySelectorAll("."+selectTaget).forEach(function(e){
        e.classList.remove("hidden");
    })
    const notSelectTaget = selectTaget=="drag" ?"gyoText":"drag";
    document.querySelectorAll("."+notSelectTaget).forEach(function(e){
        e.classList.add("hidden");
    });
}

//memoと備考の切り替え
function bikoOrMemo(selectTaget){
    document.querySelectorAll("."+selectTaget).forEach(function(e){
        e.classList.remove("hidden");
    })
    const notSelectTaget = selectTaget=="biko" ? "memo" : "biko";
    document.querySelectorAll("."+notSelectTaget).forEach(function(e){
        e.classList.add("hidden");
    });
}

function chengeColspan(selectTaget,notSelectTaget){document.querySelectorAll("."+selectTaget).forEach(function(e){
        e.classList.remove("hidden");
                })
                document.querySelectorAll("."+notSelectTaget).forEach(function(e){
                    e.classList.add("hidden");
                });
            }for (let index = 0; index <= 4; index++) {
            if(!document.querySelectorAll(".tbody")[index]) continue;
                Sortable.create(document.querySelectorAll(".tbody")[index], {handle: '.handle',chosenClass: 'chosen',
            animation: 200,scroll: document.querySelectorAll(".tbody")[index].closest('.overflow-y-auto'),scrollSensitivity: 120,scrollSpeed: 10, 
            onSort:   onSortEvent,
            onStart: function(evt) {
            evt.item.querySelectorAll("td,input,svg").forEach(function(e3){e3.style.backgroundColor="rgb(65, 160, 204)";
                                                    });
                    },onEnd: function(evt) {evt.item.querySelectorAll("td,input,svg").forEach(function(e3){e3.style.backgroundColor="";});let i=1;
                        document.querySelectorAll(".gyoTextClass").forEach(function(e){e.placeholder=i;  i++;});
            },});  
    }


function onSortEvent(){

    const trs=document.querySelectorAll(".tbody")[0].querySelectorAll('tr');
    // const trs2=document.querySelectorAll(".tbody")[1].querySelectorAll('tr');
    let idArray=[];
    trs.forEach(function(e){
        idArray.push(e.dataset.id);
    });

    for (let index = 1; index <= 4; index++) {
        if(document.querySelectorAll(".tbody")[index]){
            const trs2=document.querySelectorAll(".tbody")[index].querySelectorAll('tr');
            trs2.forEach(function(e){
                idArray.push(e.dataset.id);
            });
        }
    }
    

    $.ajax({
        type : 'post',
        // url : './sort/sai?idArray='+idArray,
        url : './sort/sai',
        dataType : 'json',
        contentType : 'application/json; charset=UTF-8',
        data: JSON.stringify({
            mitumoriId: mitumoriId,
            mitumoriKoId: mitumoriKoId??null,
            idArray: idArray
        }),
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    }).done(function(response){
        rekicheck();//並べ終わったら履歴確認
    }).fail(function(data){
        /* 通信失敗時 */
        console.log('残念');
    });
}


function addKikaku(){

    if(sizaiCode.length!=18) return;
    $.ajax({
        type : 'post',
        url : './add/kikaku',
        dataType : 'json',
        contentType : 'application/json; charset=UTF-8',
        data: JSON.stringify({
            mitumoriId: mitumoriId,
            mitumoriKoId: mitumoriKoId??null,
            code: sizaiCode
        }),
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    }).done(function(response){
        sizaiKikakuGet(sizaiCode);
    }).fail(function(data){
        /* 通信失敗時 */
        console.log('残念');
    });
}

function deleteKikaku(kikakuId){
    $.ajax({
        type : 'delete',
        url : './delete/kikaku',
        dataType : 'json',
        contentType : 'application/json; charset=UTF-8',
        data: JSON.stringify({
            kikakuId: kikakuId,
        }),
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    }).done(function(response){
        sizaiKikakuGet(sizaiCode);
    }).fail(function(data){
        /* 通信失敗時 */
        console.log('残念');
    });
}

// フォーカスした行を色変更
$(document).on('focus', '.mainTable tbody input:not([disabled])', function(e) {
    $(this).closest('tr').find('input,td,svg').each(function() {
        if (this !== e.target) {
            $(this).css('background-color', '#b4d7d8');
        }
    });
});

// フォーカスが外れたら戻す
$(document).on('blur', '.mainTable tbody input:not([disabled])', function(e) {
    $(this).closest('tr').find('input,td,svg').css('background-color', '');
});

// 四捨五入
//jsはマイナスの時は五捨六入になる
function roundDecimal(value) {
    if(value >= 0){
        return Math.round(value);
    }else{
        const value2 = Math.round(Math.abs(value));
        return -value2;
    }
}

//空にさせる
let karaKbn = false;
let karaKbn2 = false;
$(document).on("blur", ".karaEv", function () {
    karaKbn = false;
    karaKbn2 = false;
});



    // input フォーカス時
$(document).on("focus", "tbody input, .etcFocus input", function () {
    $.ajax({
        type: "post",
        url: "./add/reki/focus",
        dataType: "json",
        contentType: "application/json; charset=UTF-8",
        data: JSON.stringify({
            id: mitumoriId,
        }),
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
    }).done(function(response){

    }).fail(function(data){
        console.log("残念");
    });

});


// input ブラー時
$(document).on("blur", "tbody input, .etcFocus input", function () {
    setTimeout(() => {
        $.ajax({
            type: "post",
            url: "./add/reki/blur",
            dataType: "json",
            contentType: "application/json; charset=UTF-8",
            data: JSON.stringify({
                id: mitumoriId,
                mitumoriKoId: mitumoriKoId ?? null
            }),
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            }
        }).done(function(response){
            rekicheck();
        }).fail(function(data){
            console.log("残念");
        });
    }, 500);
});


// 労務単価自動計算ボタン
$(document).on("click", "tbody .rekiBtn, .etcFocus .rekiBtn", function () {
    $.ajax({
        type: "post",
        url: "./add/reki/focus",
        dataType: "json",
        contentType: "application/json; charset=UTF-8",
        data: JSON.stringify({
            id: mitumoriId,
        }),
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
    }).done(function(response){
        setTimeout(() => {
            $.ajax({
                type: "post",
                url: "./add/reki/blur",
                dataType: "json",
                contentType: "application/json; charset=UTF-8",
                data: JSON.stringify({
                    id: mitumoriId,
                    mitumoriKoId: mitumoriKoId ?? null
                }),
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                }
            }).done(function(response){
                rekicheck();
            }).fail(function(data){
                console.log("残念");
            });
        },500);
    }).fail(function(data){
        console.log("残念");
    });
});


function rekicheck(){
    $.ajax({
        type : 'get',
        url : './add/reki/check',
        dataType : 'json',
        contentType : 'application/json; charset=UTF-8',
        data: JSON.stringify({
            id: "{{ $mitumori->id }}",
        }),
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        }).done(function(response){
            if(response.future==true){
                document.querySelector("#future").classList.remove("disabled-link");
            }else{
                document.querySelector("#future").classList.add("disabled-link");
            }
            if(response.past==true){
                document.querySelector("#past").classList.remove("disabled-link")   ;
            }else{
                document.querySelector("#past").classList.add("disabled-link");
            }
        }).fail(function(data){
            /* 通信失敗時 */
            console.log('残念');
        });
}

  



