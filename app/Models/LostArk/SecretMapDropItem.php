<?php

namespace App\Models\LostArk;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SecretMapDropItem extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "lostark_secret_map_drop_item";
    protected $primaryKey = "id";
    protected $fillable = [
        'map_id',
        'item_code',
        'drop_count'
    ];
}
