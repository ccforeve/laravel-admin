<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/15 0015
 * Time: 下午 2:05
 */

namespace App\Http\Controllers;


use App\Models\User;

class UserController extends Controller
{
    public function index(  )
    {
        $user = User::find(session('user_id'));

        return view('index.user_center', compact('user'));
    }
}