<?php

namespace App\Models\Arknights;

use Jenssegers\Mongodb\Eloquent\Model;

class CharSkin extends Model
{
    protected $connection = 'mongodb';
    protected $primaryKey = 'id';
}