<?php

namespace App\Models\LostArk;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemMarketPrice extends Model
{
    use HasFactory;
    protected $table = "lostark_market_price";
    protected $primaryKey = "item_code";
    protected $fillable = [
        'item_code',
        'now_price',
        'now_avg_price',
        'bundle_count',
        'y_trade_count'
    ];
}
