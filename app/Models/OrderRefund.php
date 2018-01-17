<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/11 0011
 * Time: 下午 1:54
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class OrderRefund extends Model
{
    protected $fillable = ['status', 'money', 'apply_at', 'end_at'];

    public $timestamps = false;
}