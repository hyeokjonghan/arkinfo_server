<?php

namespace App\Models\LostArk;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemProduceItem extends Model
{
    use HasFactory;
    protected $table = "lostark_produce_item";
    protected $primaryKey = "id";
    protected $fillable = [
        'item_code',
        'produce_item_name',
        'produce_type',
        'produce_cost',
        'produce_price_type',
        'produce_price',
        'produce_cost_time'
    ];

    // With Item Material
    public function withItemMaterial() {
        return $this->hasMany(ItemMaterial::class, 'target_item_code', 'item_code')
        ->join('lostark_produce_item', function ($join) {
            $join->on('lostark_item_material.target_item_code','=','lostark_produce_item.item_code')
            ->on('lostark_item_material.produce_type','=','lostark_produce_item.produce_type');
        })
        ->join('lostark_item_information','lostark_item_information.item_code','lostark_item_material.item_code')
        ->select('lostark_item_information.*', 'lostark_item_material.*');
    }
}
