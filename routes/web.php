<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MitumoriSetubiKaniController;
use App\Http\Controllers\MitumoriCommonController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\DailyreportsKanriController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route::get('/', function () {
    //     return redirect('/mitumoriSetubiKani/index/1184');
    // });

    // Route::get('/scheduleDayPailot', function () {
    //     return redirect('/scheduleDayPailot');
    // });

//mitumoriKaniController
    Route::get('/mitumoriSetubiKani/index/{id}', [MitumoriSetubiKaniController::class,'index'])->name('mitumoriSetubiKani.index');
    Route::get('/mitumoriSetubiKani/index/kansetuAgain/{id}', [MitumoriSetubiKaniController::class,'kansetuAgain'])->name('mitumoriSetubiKani.kansetuAgain');//間接費再計算
    Route::get('/mitumoriSetubiKani/index/add/root.json', [MitumoriSetubiKaniController::class,'jstree']);
    Route::post('/mitumoriSetubiKani/index/sort/sai', [MitumoriSetubiKaniController::class,'sort']);
    Route::post('/mitumoriSetubiKani/index/add/kikaku', [MitumoriCommonController::class,'addKikaku']);
    Route::delete('/mitumoriSetubiKani/index/delete/kikaku', [MitumoriCommonController::class,'deleteKikaku']);
    
    Route::get('/mitumoriSetubiKani/index/past/{id}', [MitumoriSetubiKaniController::class,'go_to_the_past'])->name('mitumoriSetubiKani.past');
    Route::get('/mitumoriSetubiKani/index/future/{id}', [MitumoriSetubiKaniController::class,'go_to_the_future'])->name('mitumoriSetubiKani.future');
    Route::post('/mitumoriSetubiKani/index/add/reki/focus', [MitumoriSetubiKaniController::class,'focusReki']);//
    Route::post('/mitumoriSetubiKani/index/add/reki/blur', [MitumoriSetubiKaniController::class,'blurReki']);//
    Route::post('/mitumoriSetubiKani/index/copy/{id}', [MitumoriSetubiKaniController::class,'copy']);//
    Route::get('/mitumoriSetubiKani/index/add/reki/check', [MitumoriSetubiKaniController::class,'checkReki']);//
    Route::post('/mitumoriSetubiKani/index/keep/sizai', [MitumoriSetubiKaniController::class,'keepSizai']);
    Route::post('/mitumoriSetubiKani/index/edit/sizai', [MitumoriSetubiKaniController::class,'editSizai']);
    Route::get('/mitumoriSetubiKani/index/kansetu/ajax', [MitumoriSetubiKaniController::class,'kansetuAjax']);
    Route::post('/mitumoriSetubiKani/index/addRow/{id}', [MitumoriSetubiKaniController::class,'addRow'])->name('mitumoriSetubiKani.addRow');
    Route::post('/mitumoriSetubiKani/index/addKeihi/{id}', [MitumoriSetubiKaniController::class,'addKeihi'])->name('mitumoriSetubiKani.addKeihi');
    Route::put('/mitumoriSetubiKani/index/mitumoriUpdate/{id}', [MitumoriSetubiKaniController::class,'mitumoriUpdate'])->name('mitumoriSetubiKani.mitumoriUpdate');
    Route::post('/mitumoriSetubiKani/store', [MitumoriSetubiKaniController::class,'store'])->name('hiroiSetubiKani.store');
    Route::post('/mitumoriSetubiKani/storeKansetu', [MitumoriSetubiKaniController::class,'storeKansetu'])->name('mitumoriSetubiKai.storeKansetu');
    Route::post('/mitumoriSetubiKani/delete/{id}', [MitumoriSetubiKaniController::class,'delete'])->name("mitumoriSetubiKani.delete");
    Route::put('/mitumoriSetubiKani/index/onChange', [MitumoriSetubiKaniController::class,'mitumoriOnChange']);
    Route::put('/mitumoriSetubiKani/index/onChange/sai', [MitumoriSetubiKaniController::class,'update']);
    Route::get('/mitumoriSetubiKani/dompdf/{id}/{companyId}/{pdfType}', [MitumoriSetubiKaniController::class,'domPdf'])->name('mitumoriSetubiKani.pdf');
    Route::get('/mitumoriSetubiKani/excel/{id}/{companyId}/{pdfType}', [MitumoriSetubiKaniController::class,'excel'])->name('mitumoriSetubiKani.excel');
    Route::get('/mitumoriSetubiKani/index/romUpdate/{id}', [MitumoriSetubiKaniController::class,'romUpdate'])->name("mitumoriSetubiKani.romUpdate");
    Route::get('/mitumoriSetubiKani/index/aimitu/{id}/{companyId}/', [MitumoriSetubiKaniController::class,'aimitu'])->name("mitumoriSetubiKani.aimitu");
    Route::put('/mitumoriSetubiKani/index/aimitu/{id}/onChange/', [MitumoriSetubiKaniController::class,'mitumoriOnChange']);
    Route::put('/mitumoriSetubiKani/index/aimitu/{id}/onChange/sai', [MitumoriSetubiKaniController::class,'update']);
    


    //dayPilot
    Route::get('scheduleDayPailot',[ScheduleController::class,'index'])->name('scheduleIndex');
    Route::post('scheduleDayPailot',[ScheduleController::class,'index'])->name('scheduleIndex');
    Route::get('scheduleDayPailot/printing/{ym}/{depa}',[ScheduleController::class,'printing']);
    Route::post('scheduleDayPailot/insert',[ScheduleController::class,'insert']);
    Route::get('scheduleDayPailot/get',[ScheduleController::class,'get']);
    Route::put('scheduleDayPailot/move',[ScheduleController::class,'move']);
    Route::put('scheduleDayPailot/update',[ScheduleController::class,'update']);
    Route::delete('scheduleDayPailot/delete',[ScheduleController::class,'destroy']);


    //カレンダー
    Route::get('calendar/{id?}',[DailyreportsKanriController::class,'calendar'])->name('calendar');
    Route::post('calendar/holiday',[DailyreportsKanriController::class,'holiday']);
    Route::delete('calendar/holiday/delete',[DailyreportsKanriController::class,'holidayDelete']);
    Route::get('calendar/holiday/get',[DailyreportsKanriController::class,'holidayGet']);

    
  
});

require __DIR__.'/auth.php';
