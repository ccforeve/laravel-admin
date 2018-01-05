<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * 推广人
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function extension()
    {
        return $this->belongsTo(User::class, 'p_id');
    }

    /**
     * 经销商
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function dealer()
    {
        return $this->belongsTo(User::class, 'dealer_id');
    }
}
