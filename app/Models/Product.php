<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    //关联规格
    public function spec()
    {
        return $this->hasMany(Specification::class);
    }
}
