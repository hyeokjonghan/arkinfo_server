<?php

namespace App\Models\Arknights;


use Jenssegers\Mongodb\Eloquent\Model;

class Operators extends Model
{
    protected $connection = 'mongodb';
    protected $primaryKey = 'id';
}
