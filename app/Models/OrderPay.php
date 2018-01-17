<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/12 0012
 * Time: 下午 5:58
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class OrderPay extends Model
{
    protected $fillable = ['number', 'user_id', 'order_id', 'mode'];
}