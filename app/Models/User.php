<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;


    /**
     * 推广人
     * @return mixed
     */
    public function extension()
    {
        return $this->belongsTo(User::class, 'p_id');
    }

    /**
     * 经销商
     * @return mixed
     */
    public function dealer()
    {
        return $this->belongsTo(User::class, 'dealer_id');
    }
}
