<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyncFail extends Model
{
    use HasFactory;

    protected $table = "sync_fail";
    protected $primaryKey = "id";
    protected $fillable = [
        'table_name',
        'key_name',
        'key_value',
        'fail_type',
        'check',
        'creaetd_at',
        'updated_at'
    ];
}
