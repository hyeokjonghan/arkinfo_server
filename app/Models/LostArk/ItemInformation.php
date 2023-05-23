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
        'item_icon'
    ];
}
