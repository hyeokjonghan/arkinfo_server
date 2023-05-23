<?php

namespace App\Models\LostArk;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SecretMaps extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "lostark_secret_maps";
    protected $primaryKey = "map_id";
    protected $fillable = [
        'map_type_name'
    ];

    // 드롭아이템 With + Join MarketPrice
    public function withDropItem() {
        return $this->hasMany(SecretMapDropItem::class, 'map_id', 'map_id')
        ->join('lostark_market_price', 'lostark_market_price.item_code', 'lostark_secret_map_drop_item.item_code')
        ->join('lostark_item_information', 'lostark_item_information.item_code', 'lostark_secret_map_drop_item.item_code')
        ->select(
            'lostark_item_information.item_code',
            'lostark_item_information.item_name',
            'lostark_item_information.item_grade',
            'lostark_item_information.item_icon',
            'lostark_market_price.now_price',
            'lostark_secret_map_drop_item.map_id'
        );
    }
}
