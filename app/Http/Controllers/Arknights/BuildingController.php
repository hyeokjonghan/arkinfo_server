<?php

namespace App\Http\Controllers\Arknights;

use App\Http\Controllers\Controller;
use App\Models\Arknights\Buildings;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class BuildingController extends Controller
{
    public function getBuildingCharBuff($id) {
        $charInfo = Buildings::project(['chars.'.$id => 1])->first();
        if(isset($charInfo->chars[$id])) {
            // BuffChar
            $buffInfo = $charInfo->chars[$id]['buffChar'];
            $infraSkillList = array();
            foreach($buffInfo as $infra) {
                if(count($infra['buffData']) > 0) {
                    for($i = 0; $i < count($infra['buffData']); $i++) {
                        $infraSkillList['buffs.'.$infra['buffData'][$i]['buffId']] = 1;
                    }
                    
                }
                
            }
            $infraSkillInfo = Buildings::project($infraSkillList)->first();
            $charInfo->infra = $infraSkillInfo['buffs'];
            return $charInfo;
        } else {
            return response()->caps('not found char info', Response::HTTP_NOT_FOUND);
        }
    }
}
