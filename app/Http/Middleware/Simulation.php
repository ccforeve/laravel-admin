<?php

namespace App\Http\Middleware;

use Closure;
use Overtrue\Socialite\User as SocialiteUser;

class Simulation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!session('user_id')) {
            $user = new SocialiteUser([
                'id'       => 'oRyDvwE53Juup2z0TeLnkLcs3QDww',
                'name'     => 'begin',
                'nickname' => 'begin',
                'avatar'   => 'headimgurl',
                'email'    => null,
                'original' => [],
                'provider' => 'WeChat',
            ]);
            session([ 'wechat.oauth_user.default' => $user ]);
        }
        return $next($request);
    }
}
