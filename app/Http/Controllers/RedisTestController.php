<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class RedisTestController extends Controller
{
    public function test() {
        $redis = Redis::connection();
        $data = [
            "jjong0"=>[
                "name"=>"jjong0",
                "rank"=>5
            ],
            "jjong1"=>[
                "name"=>"jjong1",
                "rank"=>5
            ],
        ];
        $redis->set('operator_list',json_encode($data, true));

        return $redis->get('key');
    }

    public function getTest() {
        $redis = Redis::connection();
        $data = $redis->get('operator_list');
        return $data;
    }
}
