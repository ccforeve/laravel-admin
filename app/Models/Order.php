<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    //关联产品
    public function product()
    {
        return $this->hasOne(Product::class);
    }

    //关联物流
    public function logistic()
    {
        return $this->hasOne(Logistic::class);
    }

    //关联地址
    public function address()
    {
        return $this->hasOne(Address::class);
    }
}
