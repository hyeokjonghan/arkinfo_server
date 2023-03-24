<?php

namespace App\Models\Arknights;

use Jenssegers\Mongodb\Eloquent\Model;

class Skills extends Model
{
    protected $connection = 'mongodb';
    protected $primaryKey = 'id';
}