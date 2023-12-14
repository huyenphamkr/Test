<?php


use App\Http\Controllers\Controller;
use App\Http\Controllers\M01Controller;
use App\Http\Controllers\U00Controller;
use App\Http\Controllers\U01Controller;
use App\Http\Controllers\U02Controller;
use App\Http\Controllers\U04Controller;
use App\Http\Controllers\U05Controller;
use App\Http\Controllers\M02Controller;
use App\Http\Controllers\M03Controller;
use App\Http\Controllers\M04Controller;
use App\Http\Controllers\M05Controller;
use App\Http\Controllers\M06Controller;
use App\Http\Controllers\U03Controller;
use App\Http\Controllers\U032Controller;
use App\Http\Controllers\U033Controller;
use App\Http\Controllers\U034Controller;
use App\Http\Controllers\U035Controller;
use App\Http\Controllers\U036Controller;
use App\Http\Controllers\U037Controller;
use App\Http\Controllers\U038Controller;
use App\Http\Controllers\U039Controller;
use App\Http\Controllers\U0310Controller;
use App\Http\Controllers\U07Controller;
use App\Http\Controllers\U08Controller;
use App\Http\Controllers\UserLoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\U0311Controller;
use App\Http\Controllers\U0312Controller;
use App\Http\Controllers\U072Controller;
use App\Http\Controllers\U073Controller;
use App\Http\Controllers\U074Controller;
use App\Http\Controllers\U075Controller;
use App\Http\Controllers\U076Controller;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::prefix('/')->group(function () {
    Route::get('/login', [UserLoginController::class, 'index'])->name('login');
    Route::get('/logout', [UserLoginController::class, 'logout'])->name('logout');
    Route::post('/login', [UserLoginController::class, 'login']);
    Route::get('/sendMail', [U00Controller::class, 'index'])->name('u00');
    Route::post('/sendMail', [U00Controller::class, 'sendMail']);
    Route::get('/changePwd/{id}/{token}',[U00Controller::class, 'indexChangePwd'])->name('u00.changePwd')
            ->where(['id' => '[0-9]+', 'token' => '[0-9A-Za-z\-]+']);
    Route::post('/changePwd/{id}/{token}',[U00Controller::class, 'changePwd'])
            ->where(['id' => '[0-9]+', 'token' => '[0-9A-Za-z\-]+']);
    Route::post('/validate', [Controller::class, 'validate'])->name('validate');
    Route::post('/upload', [Controller::class, 'upload'])->name('upload');
});


Route::prefix('user')->group(function () {
    Route::get('/u01', [U01Controller::class, 'index'])->name('u01');
    Route::post('/u01', [U01Controller::class, 'nextPage'])->name('u01.nextPage');


    Route::prefix('u02')->group(function () {
        Route::get('/', [U02Controller::class, 'index'])->name('u02');
        Route::post('/', [U02Controller::class, 'index'])->name('u02.search');
    });


    Route::prefix('m01')->group(function(){
        Route::get('/', [M01Controller::class, 'index'])->name('m01');
        Route::get('/edit/{id?}', [M01Controller::class, 'edit'])->name('m01.edit')
            ->where(['id' => '[0-9A-Za-z\-]+']);
        Route::post('/edit/{id?}', [M01Controller::class, 'save'])->name('m01.save')
            ->where(['id' => '[0-9A-Za-z\-]+']);
        Route::get('/memory/{id?}', [M01Controller::class,'memory'])->name('m01.showMemo');
        Route::post('/memory/{id?}', [M01Controller::class,'saveMemory'])->name('m01.saveMemo');
        Route::get('/invitation/{id?}', [M01Controller::class,'invitation'])->name('m01.showInvi');
        Route::post('/invitation/{id?}', [M01Controller::class,'saveInvitation'])->name('m01.saveInvi');
        Route::get('/contract/{id?}',[M01Controller::class,'contract'])->name('m01.contract');
        Route::post('/contract/{id?}',[M01Controller::class,'saveContract'])->name('m01.saveCont');
    });


    Route::prefix('m02')->group(function () {
        Route::get('/', [M02Controller::class, 'index'])->name('m02');
        Route::get('/edit/{id?}', [M02Controller::class,'edit'])->name('m02.edit');
        Route::post('/edit/{id?}', [M02Controller::class,'save'])->name('m02.save');
    });
   
    Route::prefix('m03')->group(function () {
        Route::get('/', [M03Controller::class, 'index'])->name('m03');
        Route::post('/', [M03Controller::class, 'index'])->name('m03.search');
        Route::post('/message', [M03Controller::class, 'saveMessage'])->name('m03.saveMessage');
        Route::get('/edit/{id?}', [M03Controller::class, 'showStaffList'])->name('m03.showStaffList');
        Route::post('/edit/{id?}', [M03Controller::class, 'saveStaffList'])->name('m03.saveStaffList');
    });


    Route::prefix('m04')->group(function () {
        Route::get('/', [M04Controller::class, 'index'])->name('m04');
        Route::get('/edit/{id?}',[M04Controller::class,'edit'])->name('m04.edit');
        Route::post('/edit/{id?}',[M04Controller::class,'save'])->name('m04.save');
        Route::post('/search', [M04Controller::class, 'search'])->name('m04.search');
    });


    Route::prefix('m05')->group(function () {
        Route::get('/', [M05Controller::class, 'index'])->name('m05');
        Route::post('/', [M05Controller::class, 'save'])->name('m05.save');
    });
    Route::prefix('m06')->group(function () {
        Route::get('/', [M06Controller::class, 'index'])->name('m06');
        Route::post('/del/{id?}', [M06Controller::class, 'deleteRules'])->name('m06.deleteRules')
        ->where(['id' => '[0-9A-Za-z\-]+']);
        Route::get('/edit/{id?}', [M06Controller::class, 'edit'])->name('m06.edit')
        ->where(['id' => '[0-9A-Za-z\-]+']);  
        Route::post('/edit/{id?}', [M06Controller::class, 'save'])->name('m06.save')
        ->where(['id' => '[0-9A-Za-z\-]+']);
    });
    Route::prefix('u03')->group(function () {
        //u031
        Route::get('/information/{id?}', [U03Controller::class,'information'])->name('u03.showInfo')
            ->where(['id' => '[0-9A-Za-z\-]+']);
        Route::post('/information/{id?}', [U03Controller::class,'saveInformation'])->name('u03.saveInfo')
            ->where(['id' => '[0-9A-Za-z\-]+']);
        Route::delete('/information/{id?}', [U03Controller::class,'deleteInformation'])->name('u03.deleteInfo')
            ->where(['id' => '[0-9A-Za-z\-]+']);
        Route::post('/send/{id?}', [U03Controller::class,'sendMail'])->name('u03.sendMail')
            ->where(['id' => '[0-9A-Za-z\-]+']);
        //u032
        Route::get('/invitation/{id?}', [U032Controller::class,'invitation'])->name('u032.showInvi')
            ->where(['id' => '[0-9A-Za-z\-]+']);
        Route::post('/invitation/{id?}', [U032Controller::class,'saveInvitation'])->name('u032.saveInvi')
            ->where(['id' => '[0-9A-Za-z\-]+']);
        //u033
        Route::get('/contract/{id?}',[U033Controller::class,'contract'])->name('u033.contract')
            ->where(['id' => '[0-9A-Za-z\-]+']);
        Route::post('/contract/{id?}',[U033Controller::class,'saveContract'])->name('u033.saveCont')
            ->where(['id' => '[0-9A-Za-z\-]+']);
        //u034
        Route::get('/employApplication/{id?}/{token?}', [U034Controller::class,'index'])->name('u034')
            ->where(['id' => '[0-9A-Za-z\-]+']);
        Route::post('/employApplication/sendMail', [U034Controller::class,'sendMail'])->name('u034.sendMail');
        Route::post('/employApplication/{id?}/{token?}', [U034Controller::class,'save'])->name('u034.save')
            ->where(['id' => '[0-9A-Za-z\-]+']);
        //u035
        Route::get('/guarantee/{idInfo?}/{token?}', [U035Controller::class,'guarantee'])->name('u035.showGuarantee');
        Route::post('/guarantee/{idInfo?}/{token?}', [U035Controller::class,'saveGuarantee'])->name('u035.saveGuarantee');
        //u036
        Route::get('/invitationConfirm/{id?}/{token?}', [U036Controller::class,'invitationConfirm'])->name('u036.showInviConfirm')
            ->where(['id' => '[0-9A-Za-z\-]+']);
        Route::post('/invitationConfirm/{id?}/{token?}', [U036Controller::class,'saveInvitationConfirm'])->name('u036.saveInviConfirm')
            ->where(['id' => '[0-9A-Za-z\-]+']);
        //u037
        Route::get('/application/{id?}/{token?}', [U037Controller::class,'application'])->name('u037.showAppli')
            ->where(['id' => '[0-9A-Za-z\-]+']);
        Route::post('/application/{id?}/{token?}', [U037Controller::class,'saveApplication'])->name('u037.saveAppli')
            ->where(['id' => '[0-9A-Za-z\-]+']);
        //u038
        Route::get('/privateCar/{id?}/{token?}', [U038Controller::class,'privateCar'])->name('u038.privateCar')->where(['id' => '[0-9]+']);;
        Route::post('/privateCar/{id?}/{token?}', [U038Controller::class,'savePrivateCar'])->name('u038.savePrivateCar');
        //u039
        Route::get('/applicationHouses/{id?}/{token?}', [U039Controller::class,'applicationHouses'])->name('u039.showAppliHouses');
        Route::post('/applicationHouses/{id?}/{token?}', [U039Controller::class,'saveApplicationHouses'])->name('u039.saveAppliHouses');
        //u0310
        Route::get('/viewContract/{id?}/{token?}',[U0310Controller::class,'EmplContract'])->name('u0310.emplContract')
            ->where(['id' => '[0-9A-Za-z\-]+']);
        Route::post('/viewContract/{id?}/{token?}',[U0310Controller::class,'saveEmplContract'])->name('u0310.saveEmplContract')
            ->where(['id' => '[0-9A-Za-z\-]+']);
        //u0311
        Route::get('/docSign/{id?}/{token?}', [U0311Controller::class,'docSign'])->name('u0311.showDocSign');
        Route::post('/docSign/{id?}/{token?}', [U0311Controller::class,'nextPageDocSign'])->name('u0311.nextPageDocSign');
        //u0312
        Route::get('/completeProcedure/{id?}/{token?}', [U0312Controller::class,'compleProcedure'])->name('u0312.compleProcedure');
        Route::post('/completeProcedure/{id?}/{token?}', [U0312Controller::class,'backPage'])->name('u0312.backPage');
    });


    Route::prefix('u04')->group(function () {
        Route::get('/{id}', [U04Controller::class, 'index'])->name('u04');
        Route::post('/approval', [U04Controller::class,'approval'])->name('u04.approval');
        Route::post('/{id}', [U04Controller::class,'save'])->name('u04.save');
    });
    Route::prefix('u05')->group(function () {
        Route::get('/{id?}', [U05Controller::class,'index'])->name('u05')->where(['id' => '[0-9A-Za-z\-]+']);
        Route::post('/{id?}', [U05Controller::class,'save'])->name('u05.save');
    });


    Route::prefix('u08')->group(function () {
        Route::get('/', [U08Controller::class, 'index'])->name('u08');
        Route::post('/', [U08Controller::class, 'index'])->name('u08.getRules');
    });
    Route::prefix('u07')->group(function () {      
         //u071
        Route::get('/retireProc/{id?}',[U07Controller::class,'retireProc'])->name('u07.retireProc')
        ->where(['id' => '[0-9A-Za-z\-]+']);
        Route::post('/retireProc/{id?}', [U07Controller::class,'save'])->name('u07.save')
        ->where(['id' => '[0-9A-Za-z\-]+']);
        Route::delete('/retireProc/{id?}', [U07Controller::class,'deleteRetire'])->name('u07.deleteRetire')
        ->where(['id' => '[0-9A-Za-z\-]+']);
        //u072
        Route::get('/memo/{id?}', [U072Controller::class,'memo'])->name('u072.showMemo')
            ->where(['id' => '[0-9A-Za-z\-]+']);
        Route::post('/memo/{id?}', [U072Controller::class,'saveMemo'])->name('u072.saveMemo')
            ->where(['id' => '[0-9A-Za-z\-]+']);
        //u073
        Route::get('/retireProcTop/{id?}',[U073Controller::class,'index'])->name('u073.retireProcTop')
        ->where(['id' => '[0-9A-Za-z\-]+']);
        Route::post('/retireProcTop/{id?}', [U073Controller::class,'save'])->name('u073.save')
        ->where(['id' => '[0-9A-Za-z\-]+']);
        //u074
        Route::get('/retireAppli/{id?}', [U074Controller::class,'retireAppli'])->name('u074.showRetireAppli')
            ->where(['id' => '[0-9A-Za-z\-]+']);
        Route::post('/retireAppli/{id?}', [U074Controller::class,'saveRetireAppli'])->name('u074.saveRetireAppli')
            ->where(['id' => '[0-9A-Za-z\-]+']);
        //u075
        Route::get('/memoConfirm/{id?}', [U075Controller::class,'memoConfirm'])->name('u075.showMemoConfirm')
            ->where(['id' => '[0-9A-Za-z\-]+']);
        Route::post('/memoConfirm/{id?}', [U075Controller::class,'saveMemoConfirm'])->name('u075.saveMemoConfirm')
            ->where(['id' => '[0-9A-Za-z\-]+']);
        //u076
        Route::get('/compleRetire/{id?}', [U076Controller::class,'index'])->name('u076.compleRetire')
        ->where(['id' => '[0-9A-Za-z\-]+']);
        Route::post('/compleRetire/{id?}', [U076Controller::class,'backPage'])->name('u076.backPage')
        ->where(['id' => '[0-9A-Za-z\-]+']);
       
    });


});
