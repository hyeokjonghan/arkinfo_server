<?php

namespace App\Http\Controllers\LostArk;

use App\Http\Controllers\Controller;
use App\Models\LostArk\ItemInformation;
use App\Models\LostArk\ItemMarketPrice;
use App\Models\LostArk\ItemProduceItem;
use Illuminate\Http\Request;

class ItemInformationController extends Controller
{
    public function appendItem($itemInfo) {
        // code, name, grade, icon
        // Id, Name, Grade, Icon

        // 있는지 체크
        $checkItem = ItemInformation::where('item_code', $itemInfo['Id'])->get();
        if(count($checkItem) == 0) {
            $newItem = new ItemInformation([
                'item_code'=>$itemInfo['Id'],
                'item_name'=>$itemInfo['Name'],
                'item_grade'=>$itemInfo['Grade'],
                'item_icon'=>$itemInfo['Icon']
            ]);
            $newItem->save();

            // 기본 가격 세팅
            $checkItemPrice = ItemMarketPrice::where('item_code', $itemInfo['Id'])->get();
            if($checkItemPrice) {
                $itemInformationSettingController = new ItemInformationSettingController();
                $itemStatus = $itemInformationSettingController->getMarketItem($itemInfo['Id']);
                $tradeCount = 0;
                if(isset($itemStatus[0]["Stats"][1]['TradeCount'])) {
                    $tradeCount = $itemStatus[0]['Stats'][1]['TradeCount'];
                }
                $newPriceInfo = new ItemMarketPrice([
                    'item_code'=>$itemInfo['Id'],
                    'bundle_count'=>$itemInfo['BundleCount'],
                    'now_price'=>$itemInfo['CurrentMinPrice'],
                    'now_avg_price'=>$itemInfo['YDayAvgPrice'],
                    'y_trade_count'=>$tradeCount
                ]);
    
                $newPriceInfo->save();
            }

            return $newItem;
        } else {
            return 'alreay';
        }
    }
}
