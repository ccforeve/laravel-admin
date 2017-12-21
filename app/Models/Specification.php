<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Specification extends Model
{
    use SoftDeletes;

    //所属产品
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
