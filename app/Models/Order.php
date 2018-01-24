<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = ['product_id', 'product_type', 'original_price', 'pay_price', 'use_integral', 'address_id', 'complete', 'spec_id', 'activity'];

    //关联产品
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    //关联用户
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //关联物流
    public function logistic()
    {
        return $this->belongsTo(Logistic::class);
    }

    //关联地址
    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    //关联免费商品属性
    public function orderAttr()
    {
        return $this->belongsTo(OrderAttr::class);
    }

    public function orderRefund(  )
    {
        return $this->belongsTo(OrderRefund::class);
    }

    public function orderError( $order_id )
    {
        $order = Order::find($order_id);
        if($order->status == 1) {
            return ['state' => false, 'error' => '订单不可重复支付'];
        } elseif($order->delete_at != '') {
            return ['state' => false, 'error' => '订单已删除'];
        } elseif(Carbon::parse($order->update_at)->lt(Carbon::now()->subDays(7))) {
            return ['state' => false, 'error' => '订单已失效'];
        } else{
            return ['state' => true];
        }
    }
}
