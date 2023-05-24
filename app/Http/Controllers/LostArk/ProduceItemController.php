<?php

namespace App\Http\Controllers\LostArk;

use App\Http\Controllers\Controller;
use App\Models\LostArk\ItemProduceItem;
use Illuminate\Http\Request;

class ProduceItemController extends Controller
{
    public function getMarketProduceItemList() {
        $query = ItemProduceItem::select(
            'lostark_produce_item.*',
            'lostark_market_price.now_price',
            'lostark_market_price.now_avg_price',
            'lostark_market_price.bundle_count',
            'lostark_market_price.y_trade_count'
        )->join('lostark_market_price','lostark_produce_item.item_code','lostark_market_price.item_code')
        ->with(['withItemMaterial'])
        ->get();

        return $query;
    }

    // 추가 데이터 세팅
    public function appendProduceItemInit() {
        $appendDataInitFirst = [
            [
                'item_code'=>6861007,
                'produce_item_name'=>'하급 오레하 융화 재료',
                'produce_type'=>'1',
                'produce_cost'=>144,
                'produce_price'=>203,
                'produce_cost_time'=>"00:30:00"
            ],
            [
                'item_code'=>6861007,
                'produce_item_name'=>'하급 오레하 융화 재료',
                'produce_type'=>'2',
                'produce_cost'=>144,
                'produce_price'=>203,
                'produce_cost_time'=>"00:30:00"
            ],

            [
                'item_code'=>6861008,
                'produce_item_name'=>'중급 오레하 융화 재료',
                'produce_type'=>'1',
                'produce_cost'=>216,
                'produce_price'=>205,
                'produce_cost_time'=>"00:45:00"
            ],
            [
                'item_code'=>6861008,
                'produce_item_name'=>'중급 오레하 융화 재료',
                'produce_type'=>'2',
                'produce_cost'=>216,
                'produce_price'=>205,
                'produce_cost_time'=>"00:45:00"
            ],

            [
                'item_code'=>6861009,
                'produce_item_name'=>'상급 오레하 융화 재료',
                'produce_type'=>'1',
                'produce_cost'=>288,
                'produce_price'=>250,
                'produce_cost_time'=>"01:00:00"
            ],
            [
                'item_code'=>6861009,
                'produce_item_name'=>'상급 오레하 융화 재료',
                'produce_type'=>'2',
                'produce_cost'=>288,
                'produce_price'=>250,
                'produce_cost_time'=>"01:00:00"
            ],

            [
                'item_code'=>6861011,
                'produce_item_name'=>'최상급 오레하 융화 재료',
                'produce_type'=>'1',
                'produce_cost'=>360,
                'produce_price'=>300,
                'produce_cost_time'=>"01:15:00"
            ],
            [
                'item_code'=>6861011,
                'produce_item_name'=>'최상급 오레하 융화 재료',
                'produce_type'=>'2',
                'produce_cost'=>360,
                'produce_price'=>300,
                'produce_cost_time'=>"01:15:00"
            ],
        ];
        ItemProduceItem::insert($appendDataInitFirst);

    }
}
