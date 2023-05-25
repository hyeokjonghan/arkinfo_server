<?php

namespace App\Http\Controllers\LostArk;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Lib\CURLController;
use App\Models\LostArk\ItemInformation;
use App\Models\LostArk\ItemMarketPrice;
use App\Models\LostArk\ItemMaterial;
use App\Models\LostArk\ItemProduceItem;
use Illuminate\Http\Request;

class ItemInformationSettingController extends Controller
{
    const LOSTARK_API_URL = "https://developer-lostark.game.onstove.com";
    const BATTLE_ITEM_INVEN = 30411;
    const SPECIAL_ITEM_INVEN = 30700;

    const ENFORCE_MARKET_CATEGORY = 50000;
    const BATTLE_MARKET_CATEGORY = 60000;
    const LIFE_MARKET_CATEGORY = 90000;

    public function marketSearch($searchOption)
    {
        $curlController = new CURLController();
        $header = [
            "accept: application/json",
            "authorization: bearer " . env('LOSTAPI_KEY'),
            "content-Type: application/json"
        ];

        $body = json_encode($searchOption, true);
        $returnData = $curlController->postCURL(ItemInformationSettingController::LOSTARK_API_URL . "/markets/items", $body, $header);
        return $returnData;
    }

    public function getMarketItem($itemId)
    {
        $curlController = new CURLController();
        $header = [
            "accept: application/json",
            "authorization: bearer " . env('LOSTAPI_KEY')
        ];

        $returnData = $curlController->getCURL(ItemInformationSettingController::LOSTARK_API_URL . "/markets/items/" . $itemId, $header);
        return json_decode($returnData['data'], true);
    }

    public function auctionSearch($searchOption)
    {
        $curlController = new CURLController();
        $header = [
            "accept: application/json",
            "authorization: bearer " . env('LOSTAPI_KEY'),
            "content-Type: application/json"
        ];

        $body = json_encode($searchOption, true);
        $returnData = $curlController->postCURL(ItemInformationSettingController::LOSTARK_API_URL . "/auctions/items", $body, $header);
        return $returnData;
    }

    // 비밀지도 드랍 아이템 데이터 초기화 (일회용)
    public function secretMapInformationInit()
    {
        $itemInformationController = new ItemInformationController();
        // 재련 추가재료 + 태양으로 검색
        $searchBody = [
            "sort" => "grade",
            "categoryCode" => 50020,
            "ItemName" => "태양",
            "PageNo" => 0,
            "SortCondition" => "ASC"
        ];
        $appendData = $this->marketSearch($searchBody); // TEST
        $appendData = json_decode($appendData['data'], true)['Items'];

        foreach ($appendData as $item) {
            $itemInformationController->appendItem($item);
        }

        $searchBody = [
            "sort" => "grade",
            "categoryCode" => 50010,
            "ItemName" => "명예의 파편 주머니(대)",
            "PageNo" => 0,
            "SortCondition" => "ASC"
        ];

        $appendData = $this->marketSearch($searchBody); // TEST
        $appendData = json_decode($appendData['data'], true)['Items'];
        foreach ($appendData as $item) {
            $itemInformationController->appendItem($item);
        }
    }

    // 보석 초기 데이터 세팅
    public function defaultJewelInit()
    {
        $first = new ItemInformation([
            'item_code' => 65021010,
            'item_name' => "1레벨 멸화의 보석",
            'item_grade' => "고급",
            'item_icon' => "https://cdn-lostark.game.onstove.com/EFUI_IconAtlas/Use/Use_9_46.png"
        ]);
        $first->save();

        $second = new ItemInformation([
            'item_code' => 65022010,
            'item_name' => "1레벨 홍염의 보석",
            'item_grade' => "고급",
            'item_icon' => "https://cdn-lostark.game.onstove.com/EFUI_IconAtlas/Use/Use_9_56.png"
        ]);
        $second->save();

        $thrid = new ItemMarketPrice([
            'item_code' => 65021010,
            'now_price' => 18,
            'now_avg_price' => 0,
            'bundle_count' => 1,
            'y_trade_count' => 0
        ]);
        $thrid->save();

        $forth = new ItemMarketPrice([
            'item_code' => 65022010,
            'now_price' => 18,
            'now_avg_price' => 0,
            'bundle_count' => 1,
            'y_trade_count' => 0
        ]);
        $forth->save();
    }

    // 생활 재료 초기 데이터 세팅
    public function lifeItemInit()
    {
        $itemInformationController = new ItemInformationController();
        for ($i = 90200; $i <= 90700; $i += 100) {

            $searchOption = [
                "sort" => "grade",
                "categoryCode" => $i,
                "pageNo" => 0,
                "SortCondition" => "ASC"
            ];
            $appendData = $this->marketSearch($searchOption);
            $appendData = json_decode($appendData['data'], true)['Items'];
            foreach ($appendData as $item) {
                $itemInformationController->appendItem($item);
            }
        }
    }

    // 배틀 아이템 초기 데이터 세팅
    public function battleItemInit()
    {
        $itemInformationController = new ItemInformationController();
        for ($i = 60200; $i <= 60500; $i += 100) {

            $searchOption = [
                "sort" => "grade",
                "categoryCode" => $i,
                "pageNo" => 0,
                "SortCondition" => "ASC"
            ];
            $appendData = $this->marketSearch($searchOption);
            $appendData = json_decode($appendData['data'], true)['Items'];
            foreach ($appendData as $item) {
                $itemInformationController->appendItem($item);
            }
        }
    }

    // 융화 재료 초기 데이터 세팅
    public function unionItemInit()
    {
        $itemInformationController = new ItemInformationController();
        $searchOption = [
            "sort" => "grade",
            "categoryCode" => 50010,
            "ItemName" => "융화",
            "pageNo" => 0,
            "SortCondition" => "ASC"
        ];
        $appendData = $this->marketSearch($searchOption);
        $appendData = json_decode($appendData['data'], true)['Items'];
        foreach ($appendData as $item) {
            $itemInformationController->appendItem($item);
        }
    }

    // 제작 재료 초기 데이터 세팅
    public function materialItemInitStart()
    {
        // $this->materialItemInit(ItemInformationSettingController::BATTLE_ITEM_INVEN);
        $this->materialItemInit(ItemInformationSettingController::SPECIAL_ITEM_INVEN);
    }

    public function materialItemInit($category) {
        if (substr(php_uname(), 0, 7) == "Windows") {
            $battleItemData = shell_exec('python ' . app_path() . '/Py/ItemMaterial.py ' . $category);
            $battleItemData = sapi_windows_cp_conv(sapi_windows_cp_get('oem'), 65001, $battleItemData);
        } else {
            $battleItemData = shell_exec('LANG="ko_KR.UTF-8" python ' . app_path() . '/Py/ItemMaterial.py ' . ItemInformationSettingController::BATTLE_ITEM_INVEN);
        }

        $battleItemData = json_decode($battleItemData, true);

        foreach ($battleItemData as $item) {
            if (isset($item['item_code'])) {
                // produce item setting
                $checkProduceItem = ItemProduceItem::where('item_code',$item['item_code'])->get();
                if(count($checkProduceItem) == 0) {
                    $produceItem = new ItemProduceItem(
                        [
                            'item_code' => $item['item_code'],
                            'produce_item_name' => $item['item_name'],
                            'produce_type' => $item['produce_type'],
                            'produce_cost' => isset($item['produce_cost']) ? $item['produce_cost'] : 0,
                            'produce_price_type' => isset($item['produce_price_type']) ? $item['produce_price_type'] : '0',
                            'produce_price' => isset($item['produce_price']) ? $item['produce_price'] : 0,
                            'produce_cost_time' => isset($item['produce_cost_time']) ? $item['produce_cost_time'] : '00:00:00'
                        ]
                    );
                    $produceItem->save();
                }

                // material Item setting
                if(isset($item['materials'])) {
                    foreach($item['materials'] as $material) {
                        $checkMaterial = ItemMaterial::where('item_code', $material['item_code'])
                        ->where('target_item_code', $material['target_item_code'])
                        ->get();
                        if(count($checkMaterial) == 0) {
                            $materialItem = new ItemMaterial($material);
                            $materialItem->save();
                        }
                    }
                }
            }
        }

        return $battleItemData;
    }

    // 데이터 갱신 및 추가 데이터 세팅
    public function syncItemData($categoryCode = 90000) {
        $itemInformationController = new ItemInformationController();
        $itemData = [];
        $pageNo = 1;
        $searchBool = true;
        while($searchBool) {
            $searchOption = [
                "Sort"=>"GRADE",
                "CategoryCode"=>$categoryCode,
                "PageNo"=>$pageNo,
                "SortCondition"=>"ASC"
            ];

            $itemSearchData = $this->marketSearch($searchOption);
            $itemSearchData = json_decode($itemSearchData['data'], true)['Items'];
            if(count($itemSearchData) > 0) {
                $itemData = array_merge($itemData, $itemSearchData);
                $pageNo++;
            } else {
                $searchBool = false;
            }   
        }

        for($i = 0; $i < count($itemData); $i++) {
            $itemInformationController->setItem($itemData[$i]);
        }

        return 'success';
    }

    // 보석 가격 최신화
    public function jewelPriceSync() {
        $searchOption = [
            "Sort"=>"BUY_PRICE",
            "CategoryCode"=>210000,
            "ItemTier"=>3,
            "ItemName"=>"홍염",
            "PageNo"=>1,
            "SortCondition"=>"ASC"
        ];
        $itemInfo = json_decode($this->auctionSearch($searchOption)['data'],true)['Items'];
        if(count($itemInfo) > 0) {
            ItemMarketPrice::where('item_code',65022010)->update(['now_price'=>$itemInfo[0]['AuctionInfo']['BuyPrice']]);
        }
        
        $searchOption = [
            "Sort"=>"BUY_PRICE",
            "CategoryCode"=>210000,
            "ItemTier"=>3,
            "ItemName"=>"멸화",
            "PageNo"=>1,
            "SortCondition"=>"ASC"
        ];

        $itemInfo = json_decode($this->auctionSearch($searchOption)['data'],true)['Items'];
        if(count($itemInfo) > 0) {
            ItemMarketPrice::where('item_code',65021010)->update(['now_price'=>$itemInfo[0]['AuctionInfo']['BuyPrice']]);
        }
    }
}
