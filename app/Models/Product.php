<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    public function setPhotoAttribute($pictures)
    {
        if (is_array($pictures)) {
            $this->attributes['photo'] = json_encode($pictures);
        }
    }

    public function getPhotoAttribute($pictures)
    {
        return json_decode($pictures, true);
    }

    //关联规格
    public function spec()
    {
        return $this->hasMany(Specification::class);
    }
}
