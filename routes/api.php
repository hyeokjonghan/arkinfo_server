<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\Arknights\RecruitmentTagController;
use App\Http\Controllers\Arknights\OperatorController;
use App\Http\Controllers\Arknights\SetDataController;
use App\Http\Controllers\Arknights\BuildingController;
use App\Http\Controllers\Arknights\ItemsController;
use App\Http\Controllers\LostArk\ItemInformationController;
use App\Http\Controllers\LostArk\ItemInformationSettingController;
use App\Http\Controllers\LostArk\SecretMapController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


// Route::get('/test', [SetDataController::class, 'setCharSync']);

Route::prefix('/lostark')->group(function() {
    // Route::prefix('/setting')->group(function() {
    //     Route::get('/test', [SecretMapController::class, 'setDropItem']);
    // });

    Route::prefix('/item')->group(function() {
        Route::get('/list', [ItemInformationController::class, 'selectItemList']);
        Route::get('/life/list', [ItemInformationController::class, 'lifeItemList']);
    });

    Route::prefix('/secret')->group(function() {
        Route::get('/', [SecretMapController::class, 'selectSecretMap']);
    });
});

Route::prefix('/recruitment')->group(function() {
    Route::get('/op/list',[OperatorController::class,'getRecruitmentOp']);
    Route::get('/tag/list',[RecruitmentTagController::class,'all']);
    
});

Route::prefix('/operator')->group(function() {
    // Route::get('/test', [OperatorController::class,'setAvartarImg']);
    Route::get('/list', [OperatorController::class,'searchOperator']);
    Route::get('/{id}', [OperatorController::class,'getOperator']);

    Route::get('/infra/{id}', [BuildingController::class, 'getBuildingCharBuff']); 
});

Route::prefix('/item')->group(function() {
    Route::get('/search', [ItemsController::class, 'getItems']);
});

Route::prefix('user')->group(function() {

    // 일반 로그인.. SNS 관련 처리 할 때 추가 편집 해줘야 함
    Route::post('/login', [ApiAuthController::class, 'createToken']);
    Route::post('/token/refersh', [ApiAuthController::class, 'tokenRefresh']);

    // 일반 회원가입
    Route::post('/register', [ApiAuthController::class, 'createUser']);
});

Route::prefix('upload')->group(function() {
    Route::post('/{uploadDivision}',[UploadController::class,'fileUpload']);
});

// 로그인 하고 사용 할 수 있는 API
Route::middleware('auth:api')->group(function() {
    // 관리자만 사용 할 수 있는 API
    
    // 일반 유저도 사용 할 수 있는 API

});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
