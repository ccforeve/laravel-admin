<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UseIntegral extends Model
{
    use SoftDeletes;

    protected $table = 'use_integrals';

    protected $fillable = ['user_id', 'mean', 'order_id', 'use_integral', 'type', 'status'];

    public function status($integral, $order_id, $status)
    {
        if($integral) {
            UseIntegral::where('order_id', $order_id)->update(['status' => $status]);
        }
    }
}
