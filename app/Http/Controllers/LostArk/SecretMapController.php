<?php

namespace App\Http\Controllers\LostArk;

use App\Http\Controllers\Controller;
use App\Models\LostArk\SecretMapDropItem;
use App\Models\LostArk\SecretMaps;
use Illuminate\Http\Request;

class SecretMapController extends Controller
{
    public function selectSecretMap() {
        $scretMaps = SecretMaps::select(
            'lostark_secret_maps.map_id',
            'lostark_secret_maps.map_type_name',
        )
        ->with(['withDropItem'])
        ->get();
        return $scretMaps;
    }

    public function setDropItem() {
        $insertData = [
            // 파푸니카
            [
                'map_id'=>1,
                'item_code'=>66111123,  // 가호
                'drop_count'=>4
            ],
            [
                'map_id'=>1,
                'item_code'=>66111122,  // 축복
                'drop_count'=>4
            ],
            [
                'map_id'=>1,
                'item_code'=>66111121,  // 은총
                'drop_count'=>8
            ],
            [
                'map_id'=>1,
                'item_code'=>65021010,  // 명파 (대)
                'drop_count'=>8
            ],
            [
                'map_id'=>1,
                'item_code'=>65021010,  // 보석
                'drop_count'=>28
            ],
            
            // 베른 남부
            [
                'map_id'=>2,
                'item_code'=>66111123,  // 가호
                'drop_count'=>4
            ],
            [
                'map_id'=>2,
                'item_code'=>66111122,  // 축복
                'drop_count'=>8
            ],
            [
                'map_id'=>2,
                'item_code'=>66111121,  // 은총
                'drop_count'=>12
            ],
            [
                'map_id'=>2,
                'item_code'=>65021010,  // 명파 (대)
                'drop_count'=>8
            ],
            [
                'map_id'=>2,
                'item_code'=>65021010,  // 보석
                'drop_count'=>40
            ],

            // 볼다이크
            [
                'map_id'=>3,
                'item_code'=>66111123,  // 가호
                'drop_count'=>4
            ],
            [
                'map_id'=>3,
                'item_code'=>66111122,  // 축복
                'drop_count'=>8
            ],
            [
                'map_id'=>3,
                'item_code'=>66111121,  // 은총
                'drop_count'=>16
            ],
            [
                'map_id'=>3,
                'item_code'=>65021010,  // 명파 (대)
                'drop_count'=>8
            ],
            [
                'map_id'=>3,
                'item_code'=>65021010,  // 보석
                'drop_count'=>48
            ],
        ];

        SecretMapDropItem::insert($insertData);

    }
}
