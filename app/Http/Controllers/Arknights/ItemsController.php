<?php

namespace App\Http\Controllers\Arknights;

use App\Http\Controllers\Controller;
use App\Models\Arknights\Items;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

class ItemsController extends Controller
{
    public function getItems(Request $request) {
        $validator = [
            'item_id'=>'required | array'
        ];
        $validatorCheck = Validator::make($request->all(), $validator);
        if($validatorCheck->fails()) {
            return response($validatorCheck->errors()->all(), Response::HTTP_METHOD_NOT_ALLOWED);
        }

        return Items::whereIn('itemId',$request->item_id)->get();
    }

    
}
