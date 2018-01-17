<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Integral extends Model
{
    public function order(  )
    {
        return $this->belongsTo(Order::class);
    }

     /**
     * 获得用户的积分
     */
    public function integral($use_id, $type){
        $bef_integral = Integral::where(["user_id"=>$use_id,'type'=>$type,'status'=>2])->sum('integral');
        $for_integral = UseIntegral::where(["user_id"=>$use_id,'type'=>$type])->where('status', '<>', 2)->sum('use_integral');
        $integral = $bef_integral-$for_integral;

        return $integral;
    }
}
