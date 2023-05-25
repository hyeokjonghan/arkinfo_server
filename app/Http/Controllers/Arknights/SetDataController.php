<?php

namespace App\Http\Controllers\Arknights;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Lib\CURLController;
use App\Http\Controllers\SyncFailController;
use App\Models\Arknights\Buildings;
use App\Models\Arknights\CharSkin;
use App\Models\Arknights\Items;
use App\Models\Arknights\Operators;
use App\Models\Arknights\Range;
use App\Models\Arknights\Skills;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Constraint\Operator;

class SetDataController extends Controller
{
    private $curlController;

    public function __construct()
    {
        $this->curlController = new CURLController();
    }

    public function setRange()
    {
        $rangeData = $this->curlController->getCURL(env('AWS_CLOUDFRONT_S3_URL').'/gamedata/ko_KR/'.env('LAST_DATA_UPDATED').'/excel/range_table.json',[],[]);
        $rangeData = $rangeData['data'];
        $rangeData = json_decode($rangeData, true);
        foreach($rangeData as $range) {
            Range::insert($range);
        }
        return Range::all();
    }

    public function setSkill() {
        $skillData = $this->curlController->getCURL(env('AWS_CLOUDFRONT_S3_URL').'/gamedata/ko_KR/'.env('LAST_DATA_UPDATED').'/excel/skill_table.json',[],[]);
        $skillData = $skillData['data'];
        $skillData = json_decode($skillData, true);
        foreach($skillData as $skill) {
            Skills::insert($skill);
        }
        
        return 'success';

    }

    public function setCharterSkin() {
        $skinData = $this->curlController->getCURL(env('AWS_CLOUDFRONT_S3_URL').'/gamedata/ko_KR/'.env('LAST_DATA_UPDATED').'/excel/skin_table.json',[],[]);
        $skinData = $skinData['data'];
        $skinData = json_decode($skinData, true);
        $skinData = $skinData['charSkins'];
        foreach($skinData as $skin) {
            CharSkin::insert($skin);
        }
        return 'success';
    }

    public function setItem() {
        $itemData = $this->curlController->getCURL(env('AWS_CLOUDFRONT_S3_URL').'/gamedata/ko_KR/'.env('LAST_DATA_UPDATED').'/excel/item_table.json',[],[]);
        $itemData = $itemData['data'];
        $itemData = json_decode($itemData, true);
        $itemData = $itemData['items'];
        foreach($itemData as $item) {
            Items::insert($item);
        }
        return 'success';
    }

    /* 여기서 부터 동기화를 위한 코드 */
    // Building 데이터 동기화
    public function setBuildingsSync() {
        // 트랜잭션 시작
        $jsonData = $this->curlController->jsonGetCURL("https://raw.githubusercontent.com/Kengxxiao/ArknightsGameData/master/ko_KR/gamedata/excel/building_data.json");
        
        // 기존 데이터 delete
        DB::transaction(function () use ($jsonData) {
            Buildings::truncate();
            Buildings::insert(json_decode($jsonData['data'], true));
        });
        // 트랜잭션 종료
        return 'success';
    }

    // 아이템 데이터 + 아이템 이미지 동기화
    public function setItemSync() {
        $itemData = $this->curlController->jsonGetCURL("https://raw.githubusercontent.com/Kengxxiao/ArknightsGameData/master/ko_KR/gamedata/excel/item_table.json");
        $itemData = $itemData['data'];
        $itemData = json_decode($itemData, true);
        $itemData = $itemData['items'];
        $nowItemCount = Items::count();
        // 전체 카운트 가져와서 비교하고, 수가 다르면 없는 데이터 찾아서 insert.
        print_r(count($itemData));
        if(count($itemData) > $nowItemCount) {
            $diffArray = $itemData;
            $allItemId = Items::select('itemId')->get();
            foreach($allItemId as $item) {
                if(isset($itemData[$item->itemId])) {
                    unset($diffArray[$item->itemId]);
                }
            }
            
            // 이미지 처리
            foreach($diffArray as $item) {
                $data = $this->curlController->jsonGetCURL("https://raw.githubusercontent.com/Aceship/Arknight-Images/main/items/".$item['iconId'].".png");
                $imageData = $data['data'];
                $syncFailController = new SyncFailController();
                if($data['code'] == '404') {
                    // 이미지 리소스 없음.
                    $syncFailController->appendFailSync('items','iconId',$item['iconId'],SyncFailController::UPLOAD_IMAGE_RESOURCE_NOT_FOUND);
                } else {
                    // 테스트가 안되었네...
                    $checkFile = Storage::disk('s3')->exists('/items/'.$item['iconId'].'.png');
                    if(!$checkFile) {
                        // 만약 없으면 S3에 업로드 한다.
                        try {
                            Storage::disk('s3')->put('/items/'.$item['iconId'].'png',$imageData);
                        } catch(Error $error) {
                            // 실패시 DB 입력 하고 패스
                            $syncFailController->appendFailSync('items','iconId',$item['iconId'],SyncFailController::UPLOAD_FAIL);
                        }
                    }
                }

                try {
                    Items::insert($item);
                } catch(Error $error) {
                    $syncFailController->appendFailSync('items','itemId', $item['itemId'], SyncFailController::INSERT_FAIL);
                }
            }

            
            return 'success';
        } else {
            return 'end';
        }
    }

    public function setOperatorKey () {
        $charData = $this->curlController->jsonGetCURL("https://raw.githubusercontent.com/Kengxxiao/ArknightsGameData/master/ko_KR/gamedata/excel/character_table.json")['data'];
        $charData = json_decode($charData, true);
        $setData = [];
        foreach($charData as $key => $op) {
            array_push($setData, [
                'name'=>$op['name'],
                'operatorId'=>$key
            ]);

            Operators::where('name', $op['name'])
            ->where('position', $op['position'])
            ->where('profession', $op['profession'])
            ->update(['operatorId'=>$key]);
        }

        return $setData;
    }

    // 오퍼레이터 동기화 + 스킨 동기화 + 태그 정보 세팅
    public function setCharSync() {
        // 이미지 없으면 가져 와야 함
        // 스킨 데이터 세팅 해야 함
        // 태그 계산기 쪽은 수동을 처리 해야 함 (데이터가 존재 하지 않음)
        $charData = $this->curlController->jsonGetCURL("https://raw.githubusercontent.com/Kengxxiao/ArknightsGameData/master/ko_KR/gamedata/excel/character_table.json")['data'];
        $skinData = $this->curlController->jsonGetCURL("https://raw.githubusercontent.com/Kengxxiao/ArknightsGameData/master/ko_KR/gamedata/excel/skin_table.json")['data'];
        
        /* 
        추가 세팅 해줘야 하는 것 : isRecruitment => false
        default_card_img
        default_avartar_img
        recruitmentTagCodeList => 기본적으로 불능이라 빈 값 넣어주자
        */ 
        $nowCharData = Operators::count();
        $charData = json_decode($charData, true);
        $allSkinData = CharSkin::select('skinId')->get();;
        $skinData = json_decode($skinData, true)['charSkins'];
        
        if(count($skinData) > count($allSkinData)) {
            $skinDiffArray = $skinData;
            foreach($allSkinData as $skin) {
                if(isset($skinData[$skin->skinId])) {
                    unset($skinDiffArray[$skin->skinId]);
                }
            }
            $syncFailController = new SyncFailController();
            foreach($skinDiffArray as $skin) {
                $avartarImg = $this->curlController->jsonGetCURL("https://raw.githubusercontent.com/Aceship/Arknight-Images/portraits/".$skin['avatarId'].".png");
                // avatarId는 무조건 존재, 이미지 못찾으면 에러 판정
                if($avartarImg['code'] == 404) {
                    $syncFailController->appendFailSync('char_skin','avatarId',$skin['avatarId'],SyncFailController::UPLOAD_IMAGE_RESOURCE_NOT_FOUND);
                } else {
                    $checkFile = Storage::disk('s3')->exists('/avatars/'.$skin['avatarId'].'.png');
                    if(!$checkFile) {
                        // 만약 없으면 S3에 업로드 한다.
                        try {
                            Storage::disk('s3')->put('/avatars/'.$skin['avatarId'].'png',$avartarImg['data']);
                        } catch(Error $error) {
                            // 실패시 DB 입력 하고 패스
                            $syncFailController->appendFailSync('char_skin','avatarId',$skin['avatarId'],SyncFailController::UPLOAD_FAIL);
                        }
                    }
                }
                // portraitId 는 없을 수 있음 (토큰의 경우)
                if($skin['portraitId'] !== null) {
                    $portraitImg = $this->curlController->jsonGetCURL("https://raw.githubusercontent.com/Aceship/Arknight-Images/portraits/".$skin['portraitId'].".png");
                    if($portraitImg['code'] == 404) {
                        $syncFailController->appendFailSync('char_skin','portraitId',$skin['portraitId'],SyncFailController::UPLOAD_IMAGE_RESOURCE_NOT_FOUND);
                    } else {
                        $checkFile = Storage::disk('s3')->exists('/portraits/'.$skin['portraitId'].'.png');
                        if(!$checkFile) {
                            // 만약 없으면 S3에 업로드 한다.
                            try {
                                Storage::disk('s3')->put('/portraits/'.$skin['portraitId'].'png',$portraitImg['data']);
                            } catch(Error $error) {
                                // 실패시 DB 입력 하고 패스
                                $syncFailController->appendFailSync('char_skin','portraitId',$skin['portraitId'],SyncFailController::UPLOAD_FAIL);
                            }
                        }
                    }
                }
                
                try {
                    CharSkin::insert($skin);
                } catch(Error $error) {
                    $syncFailController->appendFailSync('char_skin','skinId', $skin['skinId'], SyncFailController::INSERT_FAIL);
                }
            }

            return $skinDiffArray;
        }
        
        // 스킨 정보 처리 완료
        


        // 최초 갱신 키값 갱신 처리
        if(count($charData) > $nowCharData) {
            
            $diffArray = $charData;
            $allCharArray = Operators::select('operatorId')->get();
            foreach($allCharArray as $char) {
                if(isset($charData[$char->operatorId])) {
                    unset($diffArray[$char->operatorId]);
                }
            }

            // 추가 세팅 데이터
            
            $syncFailController = new SyncFailController();
            foreach($diffArray as $key => $op) {
                $diffArray[$key]['operatorId'] = $key;
                $diffArray[$key]['isRecruitment'] = false;
                $diffArray[$key]['recruitmentTagCodeList'] = [];
                $checkFile = Storage::disk('s3')->exists('/avatars/'.$key.'.png');
                if($checkFile) {
                    $diffArray[$key]['default_avartar_img'] = env('AWS_CLOUDFRONT_S3_URL').'/avatars/'.$key.'.png';;
                } else {
                    $diffArray[$key]['default_avartar_img'] = null;
                }

                $checkFile = Storage::disk('s3')->exists('/portraits/'.$key.'_1.png');
                if($checkFile) {
                    $diffArray[$key]['default_card_img'] = env('AWS_CLOUDFRONT_S3_URL').'/portraits/'.$key.'_1.png';;
                } else {
                    $diffArray[$key]['default_card_img'] = null;
                }

                try {
                    Operators::insert($diffArray[$key]);
                } catch(Error $error) {
                    $syncFailController->appendFailSync('operators','operatorId', $key, SyncFailController::INSERT_FAIL);
                }
            }
        }

        // now 355
        return 'success';
        
    }
    /* 여기 까지 */
}
