<?php

namespace App\Models\LostArk;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemMaterial extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "lostark_item_material";
    protected $primaryKey = "id";
    protected $fillable = [
        'cost',
        'produce_type',
        'item_code',
        'target_item_code'
    ];
}
