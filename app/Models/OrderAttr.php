<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/11 0011
 * Time: 上午 11:26
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class OrderAttr extends Model
{
    protected $fillable = ['packing', 'spec', 'postage'];

    public $timestamps = false;

    public function specs()
    {
        return $this->belongsTo(Specification::class, 'spec');
    }
}