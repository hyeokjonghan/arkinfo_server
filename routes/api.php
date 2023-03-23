<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\Arknights\RecruitmentTagController;
use App\Http\Controllers\Arknights\OperatorController;


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



// Route::get('/set',[OperatorController::class,'setAvartarImg']);

Route::prefix('/recruitment')->group(function() {
    // SET Op Tag List
    // Route::get('/set',[OperatorController::class,'setRecruitmentTagList']);
    
    Route::get('/op/list',[OperatorController::class,'getRecruitmentOp']);
    Route::get('/tag/list',[RecruitmentTagController::class,'all']);
    
});

Route::prefix('/operator')->group(function() {
    // Route::get('/test', [OperatorController::class,'setAvartarImg']);
    Route::get('/list', [OperatorController::class,'searchOperator']);
    Route::get('/{op}', [OperatorController::class,'getOperator']);
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
