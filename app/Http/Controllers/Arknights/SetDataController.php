<?php

namespace App\Http\Controllers\Arknights;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Lib\CURLController;
use App\Models\Arknights\CharSkin;
use App\Models\Arknights\Items;
use App\Models\Arknights\Range;
use App\Models\Arknights\Skills;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SetDataController extends Controller
{
    private $curlController;

    public function __construct()
    {
        $this->curlController = new CURLController();
    }

    public function setRange()
    {
        $rangeData = $this->curlController->getCURL(env('AWS_CLOUDFRONT_S3_URL').'/gamedata/ko_KR/'.env('LAST_DATA_UPDATED').'/excel/range_table.json');
        $rangeData = $rangeData['data'];
        $rangeData = json_decode($rangeData, true);
        foreach($rangeData as $range) {
            Range::insert($range);
        }
        return Range::all();
    }

    public function setSkill() {
        $skillData = $this->curlController->getCURL(env('AWS_CLOUDFRONT_S3_URL').'/gamedata/ko_KR/'.env('LAST_DATA_UPDATED').'/excel/skill_table.json');
        $skillData = $skillData['data'];
        $skillData = json_decode($skillData, true);
        foreach($skillData as $skill) {
            Skills::insert($skill);
        }
        
        return 'success';

    }

    public function setCharterSkin() {
        $skinData = $this->curlController->getCURL(env('AWS_CLOUDFRONT_S3_URL').'/gamedata/ko_KR/'.env('LAST_DATA_UPDATED').'/excel/skin_table.json');
        $skinData = $skinData['data'];
        $skinData = json_decode($skinData, true);
        $skinData = $skinData['charSkins'];
        foreach($skinData as $skin) {
            CharSkin::insert($skin);
        }
        return 'success';
    }

    public function setItem() {
        $itemData = $this->curlController->getCURL(env('AWS_CLOUDFRONT_S3_URL').'/gamedata/ko_KR/'.env('LAST_DATA_UPDATED').'/excel/item_table.json');
        $itemData = $itemData['data'];
        $itemData = json_decode($itemData, true);
        $itemData = $itemData['items'];
        foreach($itemData as $item) {
            Items::insert($item);
        }
        return 'success';
    }
}
