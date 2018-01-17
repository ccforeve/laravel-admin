<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;

class Userinfo
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
            $info = session('wechat.oauth_user')[ 'default' ];
            if ( $find = User::where('openid', $info[ 'id' ])->first() ) {
                session([ 'user_id' => $find->id ]);
            } else {
                $user = new User();
                $user->openid = $info[ 'id' ];
                $user->nickname = $info[ 'nickname' ];
                $user->head = $info[ 'avatar' ];
                //推广上级
                if($request->pid) {
                    $puser = User::find($request->pid);
                    if($puser->type == 1) {
                        $user->p_id = $puser->id;
                        $user->dealer_id = $puser->dealer_id;
                    } else {
                        $user->dealer_id = $puser->id;
                    }
                }
                $user->save();

                session([ 'user_id' => $user->id ]);
            }
        }
        return $next($request);
    }
}
