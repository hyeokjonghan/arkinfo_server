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
}
