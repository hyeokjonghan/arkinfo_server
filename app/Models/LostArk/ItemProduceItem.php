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
        // MarketPlace랑 Join?
        // Query 찍어보면서 확인해보기
        return $this->hasMany(ItemMaterial::class, 'item_code', 'target_item_code')
        ->with([
            'item_code',
            'produce_type'
        ]);
    }
}
