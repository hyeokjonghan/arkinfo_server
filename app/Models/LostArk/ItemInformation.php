<?php

namespace App\Models\LostArk;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemInformation extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "lostark_item_information";
    protected $primaryKey = "item_code";
    protected $fillable = [
        'item_code',
        'item_name',
        'item_grade',
        'item_icon',
        'category',
        'sub_category'
    ];

    public function withMarketPrice() {
        return $this->hasOne(ItemMarketPrice::class, 'item_code', 'item_code')
        ->select(
            'lostark_market_price.item_code',
            'lostark_market_price.now_price',
            'lostark_market_price.now_avg_price',
            'lostark_market_price.bundle_count',
            'lostark_market_price.y_trade_count',
        );
    }
}
