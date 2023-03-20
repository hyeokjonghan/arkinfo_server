<?php

namespace App\Http\Controllers\Arknights;

use App\Http\Controllers\Controller;
use App\Models\Arknights\RecruitmentTags;
use Illuminate\Http\Request;

class RecruitmentTagController extends Controller
{

    /* 최초 데이터 편집용 */
    public function jsonEdit() {
        $tagList = '{
            "0": {
                "name": {
                    "kr": "남성",
                    "jp": "男性",
                    "en": "Male",
                    "cn": "男性干员"
                },
                "type": "sex",
                "tagCode": 1
            },
            "1": {
                "name": {
                    "kr": "여성",
                    "jp": "女性",
                    "en": "Female",
                    "cn": "女性干员"
                },
                "type": "sex",
                "tagCode": 2
            },
            "2": {
                "name": {
                    "kr": "근거리",
                    "jp": "近距離",
                    "en": "Melee",
                    "cn": "近战位"
                },
                "type": "range",
                "tagCode": 4
            },
            "3": {
                "name": {
                    "kr": "원거리",
                    "jp": "遠距離",
                    "en": "Ranged",
                    "cn": "远程位"
                },
                "type": "range",
                "tagCode": 8
            },
            "4": {
                "name": {
                    "kr": "가드",
                    "jp": "前衛",
                    "en": "Guard",
                    "cn": "近卫"
                },
                "type": "class",
                "tagCode": 16
            },
            "5": {
                "name": {
                    "kr": "디펜더",
                    "jp": "重装",
                    "en": "Defender",
                    "cn": "重装"
                },
                "type": "class",
                "tagCode": 32
            },
            "6": {
                "name": {
                    "kr": "메딕",
                    "jp": "医療",
                    "en": "Medic",
                    "cn": "医疗"
                },
                "type": "class",
                "tagCode": 64
            },
            "7": {
                "name": {
                    "kr": "뱅가드",
                    "jp": "先鋒",
                    "en": "Vanguard",
                    "cn": "先锋"
                },
                "type": "class",
                "tagCode": 128
            },
            "8": {
                "name": {
                    "kr": "서포터",
                    "jp": "補助",
                    "en": "Supporter",
                    "cn": "辅助"
                },
                "type": "class",
                "tagCode": 256
            },
            "9": {
                "name": {
                    "kr": "스나이퍼",
                    "jp": "狙撃",
                    "en": "Sniper",
                    "cn": "狙击"
                },
                "type": "class",
                "tagCode": 512
            },
            "10": {
                "name": {
                    "kr": "스페셜리스트",
                    "jp": "特殊",
                    "en": "Specialist",
                    "cn": "特种"
                },
                "type": "class",
                "tagCode": 1024
            },
            "11": {
                "name": {
                    "kr": "캐스터",
                    "jp": "術師",
                    "en": "Caster",
                    "cn": "术师"
                },
                "type": "class",
                "tagCode": 2048
            },
            "12": {
                "name": {
                    "kr": "감속",
                    "jp": "減速",
                    "en": "Slow",
                    "cn": "减速"
                },
                "type": "property",
                "tagCode": 4096
            },
            "13": {
                "name": {
                    "kr": "강제이동",
                    "jp": "強制移動",
                    "en": "Shift",
                    "cn": "位移"
                },
                "type": "property",
                "tagCode": 8192
            },
            "14": {
                "name": {
                    "kr": "누커",
                    "jp": "爆発力",
                    "en": "Nuker",
                    "cn": "爆发"
                },
                "type": "property",
                "tagCode": 16384
            },
            "15": {
                "name": {
                    "kr": "디버프",
                    "jp": "弱化",
                    "en": "Debuff",
                    "cn": "削弱"
                },
                "type": "property",
                "tagCode": 32768
            },
            "16": {
                "name": {
                    "kr": "딜러",
                    "jp": "火力",
                    "en": "DPS",
                    "cn": "输出"
                },
                "type": "property",
                "tagCode": 65536
            },
            "17": {
                "name": {
                    "kr": "로봇",
                    "jp": "ロボット",
                    "en": "Robot",
                    "cn": "支援机械"
                },
                "type": "property",
                "tagCode": 131072
            },
            "18": {
                "name": {
                    "kr": "방어형",
                    "jp": "防御",
                    "en": "Defense",
                    "cn": "防护"
                },
                "type": "property",
                "tagCode": 262144
            },
            "19": {
                "name": {
                    "kr": "범위공격",
                    "jp": "範囲攻撃",
                    "en": "AoE",
                    "cn": "群攻"
                },
                "type": "property",
                "tagCode": 524288
            },
            "20": {
                "name": {
                    "kr": "생존형",
                    "jp": "生存",
                    "en": "Survival",
                    "cn": "生存"
                },
                "type": "property",
                "tagCode": 1048576
            },
            "21": {
                "name": {
                    "kr": "소환",
                    "jp": "召喚",
                    "en": "Summon",
                    "cn": "召唤"
                },
                "type": "property",
                "tagCode": 2097152
            },
            "22": {
                "name": {
                    "kr": "제어형",
                    "jp": "牽制",
                    "en": "Crowd Control",
                    "cn": "控场"
                },
                "type": "property",
                "tagCode": 4194304
            },
            "23": {
                "name": {
                    "kr": "지원",
                    "jp": "支援",
                    "en": "Support",
                    "cn": "支援"
                },
                "type": "property",
                "tagCode": 8388608
            },
            "24": {
                "name": {
                    "kr": "코스트+",
                    "jp": "COST回復",
                    "en": "DP-Recovery",
                    "cn": "费用回复"
                },
                "type": "property",
                "tagCode": 16777216
            },
            "25": {
                "name": {
                    "kr": "쾌속부활",
                    "jp": "高速再配置",
                    "en": "Fast-Redeploy",
                    "cn": "快速复活"
                },
                "type": "property",
                "tagCode": 33554432
            },
            "26": {
                "name": {
                    "kr": "힐링",
                    "jp": "治療",
                    "en": "Healing",
                    "cn": "治疗"
                },
                "type": "property",
                "tagCode": 67108864
            },
            "27": {
                "name": {
                    "kr": "신입",
                    "jp": "初期",
                    "en": "Starter",
                    "cn": "新手"
                },
                "type": "qualification",
                "tagCode": 134217728
            },
            "28": {
                "name": {
                    "kr": "특별채용",
                    "jp": "エリート",
                    "en": "Senior Operator",
                    "cn": "资深干员"
                },
                "type": "qualification",
                "tagCode": 268435456
            },
            "29": {
                "name": {
                    "kr": "고급특별채용",
                    "jp": "上級エリート",
                    "en": "Top Operator",
                    "cn": "高级资深干员"
                },
                "type": "qualification",
                "tagCode": 536870912
            },
            "keys": [
                "0",
                "1",
                "2",
                "3",
                "4",
                "5",
                "6",
                "7",
                "8",
                "9",
                "10",
                "11",
                "12",
                "13",
                "14",
                "15",
                "16",
                "17",
                "18",
                "19",
                "20",
                "21",
                "22",
                "23",
                "24",
                "25",
                "26",
                "27",
                "28",
                "29"
            ]
        }';
        
        $tagList = json_decode($tagList, true);
        $inputJsonTagList = [];
        $index = 1;
        foreach($tagList as $tag) {
            if(isset($tag['name'])) {
                $inputJsonTagList[] = [
                    'name'=> $tag['name'],
                    'type'=> $tag['type'],
                    'tag_code'=> $index
                ];
                $index++;
            }
        };

        return json_encode($inputJsonTagList, true);
    }

    public function all() {
        return RecruitmentTags::all();
    }
}
