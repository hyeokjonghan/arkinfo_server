<?php

namespace App\Models\Arknights;

use Jenssegers\Mongodb\Eloquent\Model;

class Range extends Model
{
    protected $connection = 'mongodb';
    protected $primaryKey = 'id';
}