<?php

namespace App\Http\Controllers\Arknights;

use App\Http\Controllers\Controller;
use App\Models\Arknights\CharSkin;
use App\Models\Arknights\Operators;
use App\Models\Arknights\Range;
use App\Models\Arknights\RecruitmentTags;
use Illuminate\Http\Request;
use PHPUnit\Framework\Constraint\Operator;

class OperatorController extends Controller
{
    // 검색용 태그 리스트 setting
    public function setRecruitmentTagList() {
        $recruitmentOpList = [
            "FO03",
            "RL06",
            "LT77",
            "FO01",
            "SS02",
            "LM20",
            "PL03",
            "RL03",
            "R155",
            "RV02",
            "RE41",
            "RL02",
            "AA01",
            "GG01",
            "JC01",
            "LM04",
            "R109",
            "AZ01",
            "LM05",
            "R110",
            "R128",
            "VC03",
            "YD05",
            "RL05",
            "ST03",
            "AA03",
            "RL01",
            "R148",
            "R158",
            "R106",
            "LT01",
            "USS2",
            "ST01",
            "JC05",
            "SR03",
            "RL04",
            "KZ03",
            "R119",
            "SI01",
            "VC01",
            "ST02",
            "R104",
            "R122",
            "R107",
            "LM10",
            "LN01",
            "LT02",
            "BS03",
            "MN03",
            "R160",
            "VC02",
            "PA15",
            "PA42",
            "PA44",
            "PA43",
            "PA61",
            "PA63",
            "PA12",
            "PA13",
            "R303",
            "A44",
            "A42",
            "RCX2",
            "KZ15",
            "FO02",
            "SW01",
            "HT03",
            "BS02",
            "SG01",
            "MN02",
            "SR27",
            "LM08",
            "AA02",
            "RR01",
            "YD01",
            "LM12",
            "GG03",
            "USS1",
            "PL04",
            "JC06",
            "PL02",
            "LM19",
            "USS3",
            "KZ04",
            "R100",
            "RB02",
            "JC03",
            "HK01",
            "R108",
            "VC04",
            "R105",
            "US10",
            "LM11",
            "SW02",
            "IU04",
            "HK03",
            "R144",
            "IU05",
            "R123",
            "PA41",
            "PA62",
            "BS04",
            "PA14",
            "PA64",
            "PA11",
            "PA65",
            "LT05",
            "A43",
            "A41",
            "RCX3",
            "RCX4"
        ];
        
        $allOpList = Operators::all();
        
        $recruitmentTagList = RecruitmentTags::select("tag_code", "name.kr", "type")->get();
        
        $recruitmentTagIndexName = [];
        foreach($recruitmentTagList as $recruitmentTag) {
            $recruitmentTagIndexName[$recruitmentTag->name['kr']] = $recruitmentTag->tag_code;
        }

        // allOpList 전체 돌면서
        // displayNumber 가 $recruitmentOpList 에 있으면 isRecruitment:true 없으면 false

        // 1성 전부 로보트임 6성 고급특별채용, 5성: 특별채용 (rarity 0,4,5)
        // position :: RANGED => 원거리:: 4, MELEE => 근거리 :: 3

        // tagList 매칭
        // profession : TANK(디팬더) :6,
        // MEDIC(메딕) : 7,
        // WARRIOR(가드): 5,
        // SPECIAL(스페셜리스트): 11,
        // SNIPER(스나이퍼) : 10,
        // PIONEER(뱅가드) : 8,
        // CASTER(캐스터) : 12,
        // SUPPORT(서포터) : 9

        // Ansible GitAction Test
        
        foreach($allOpList as $operator) {

            $updataData = [];
            // 공개모집 가능 여부 판단
            if(in_array($operator->displayNumber, $recruitmentOpList)) {
                $updataData['isRecruitment'] = true;
            } else {
                $updataData['isRecruitment'] = false;
            }            
            
            if($updataData['isRecruitment']) {

                $tagList = [];
                // 로봇, 특별채용, 고급특별채용
                switch($operator->rarity) {
                    case 0:
                        array_push($tagList, 18); // 로봇
                        break;
                    case 4:
                        array_push($tagList, 29); // 특별채용
                        break;
                    case 5:
                        array_push($tagList, 30); // 고급특별채용
                        break;
                }

                // Position
                switch($operator->position) {
                    case "RANGED":
                        array_push($tagList, 4);
                        break;
                    case "MELEE":
                        array_push($tagList, 3);
                        break;
                }

                // profession
                switch($operator->profession) {
                    case "TANK":
                        array_push($tagList, 6);
                        break;
                    case "MEDIC":
                        array_push($tagList, 7);
                        break;
                    case "WARRIOR":
                        array_push($tagList, 5);
                        break;
                    case "SPECIAL":
                        array_push($tagList, 11);
                        break;
                    case "SNIPER":
                        array_push($tagList, 10);
                        break;
                    case "PIONEER":
                        array_push($tagList, 8);
                        break;
                    case "CASTER":
                        array_push($tagList, 12);
                        break;
                    case "SUPPORT":
                        array_push($tagList, 9);
                        break;       
                }

                // tagList 매칭
                foreach($operator->tagList as $tagInfo) {
                    if(isset($recruitmentTagIndexName[$tagInfo])) {
                        array_push($tagList, $recruitmentTagIndexName[$tagInfo]);
                    }
                }

                $updataData['recruitmentTagCodeList'] = $tagList;
                
            }
            
        
            Operators::where('_id',$operator->_id)->update($updataData, ['upsert'=>true]);
        }

        return $allOpList;
    }

    // Card 이미지 setting
    public function setDefaultCardImg()
    {
        $allOpList = Operators::all();
        foreach($allOpList as $op) {
            $defaultCardImg = env('AWS_CLOUDFRONT_S3_URL').'/portraits/'.substr($op->potentialItemId,2).'_1.png';
            if($op->potentialItemId) {
                Operators::where('_id', $op->_id)->update([
                    'default_card_img'=>$defaultCardImg
                ],['upsert'=>true]);
            } else {
                Operators::where('_id', $op->_id)->update([
                    'default_card_img'=>null
                ],['upsert'=>true]);
            }
            
        }

        return true;
    }

    // avatar 이미지 setting
    public function setAvartarImg() {
        $allOpList = Operators::all();
        foreach($allOpList as $op) {
            $defaultCardImg = env('AWS_CLOUDFRONT_S3_URL').'/avatars/'.substr($op->potentialItemId,2).'.png';
            if($op->potentialItemId) {
                Operators::where('_id', $op->_id)->update([
                    'default_avartar_img'=>$defaultCardImg
                ],['upsert'=>true]);
            } else {
                Operators::where('_id', $op->_id)->update([
                    'default_avartar_img'=>null
                ],['upsert'=>true]);
            }
            
        }

        return true;
    }

    public function setPotentialItemId() {
        // MN01 :: p_char_159_peacok
        // YD09 :: p_char_4019_ncdeer
        Operators::where('displayNumber', 'YD09')
        ->update([
            'potentialItemId'=>'p_char_4019_ncdeer'
        ]);

        Operators::where('displayNumber', 'MN01')
        ->update([
            'potentialItemId'=>'p_char_159_peacok'
        ]);
    }

    // 공개모집 가능한 얘들 가져오기
    public function getRecruitmentOp() {
        return Operators::select('name', 'rarity', 'isRecruitment', 'recruitmentTagCodeList','default_card_img')->where('isRecruitment', true)->get();
    }

    // 이름 검색? 병과 검색 등이 들어 갈 수 있다
    public function searchOperator(Request $request) {
        $query = Operators::select('name', 'rarity', 'profession', 'default_avartar_img')->whereNotNull ('displayNumber')
        ->orderBy('rarity','desc');
        
        $query = $query->where(function ($q) use ($request) {
            if($request->name) {
                $q->where('name','like','%'.$request->name.'%');
            }

            if($request->rarity) {

                $tempRarity = [];
                foreach($request->rarity as $rarity) {
                    array_push($tempRarity, intval($rarity));
                }

                $q->whereIn('rarity', $tempRarity);
            }

            if($request->profession) {
                $q->whereIn('profession', $request->profession);
            }
        });

        return $query->paginate(
            $perPage=20
        );
    }

    public function getOperator(Operators $id)
    {
        $operator = $id;

        $rangeList = [];
        foreach($operator->phases as $phases) {
            array_push($rangeList, $phases['rangeId']);
        }
        $operator->rangeInfo = Range::select('*')->whereIn('id', $rangeList)->get();

        $operator->skinInfo = CharSkin::select('*')->where('charId', substr($operator->potentialItemId,2))->orderBy('displaySkin.sortId', 'asc')->get();
        return $operator;
        
    }

    
}
