<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SyncFail;
use Illuminate\Http\Request;

class SyncFailController extends Controller
{
    public const INSERT_FAIL = 1;
    public const UPLOAD_FAIL = 2; // 이미지 업로드 실패
    public const UPLOAD_IMAGE_RESOURCE_NOT_FOUND = 3; // 이미지 리소스 없음

    public function appendFailSync($tableName, $keyName, $keyValue, $failCode) {
        // 데이터가 기존에 없을 때 만 처리
        $checkCount = SyncFail::select('*')
        ->where('table_name', $tableName)
        ->where('key_name', $keyName)
        ->where('key_value', $keyValue)
        ->where('fail_type', $failCode)
        ->count();

        if($checkCount == 0) {
            $syncFail = new SyncFail([
                'table_name'=>$tableName,
                'key_name'=>$keyName,
                'key_value'=>$keyValue,
                'fail_type'=>$failCode,
                'check'=>1
            ]);
            $syncFail->save();
    
            return $syncFail;
        } else {
            return 'already';
        }
    }

}
