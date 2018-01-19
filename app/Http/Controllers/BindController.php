<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/19 0019
 * Time: 上午 9:23
 */

namespace App\Http\Controllers;


use App\Http\Controllers\TraitFunction\Notice;
use App\Models\User;
use Illuminate\Http\Request;

class BindController extends Controller
{
    use Notice;

    /**
     * 绑定账户页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $user = User::find(session('user_id'));

        return view('index.card_bind', compact('user'));
    }

    /**
     * 发送绑定账户数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cardBind(Request $request)
    {
        if(isset($request->again)) {
            $phone = session('phone');
        } else {
            $data = [
                'bank_name'     => $request->bank_name,
                'bank_number'   => $request->bank_number,
                'bank_username' => $request->bank_username,
                'phone'         => $request->phone
            ];
            session($data);
            $phone = $request->phone;
            User::where('id', session('user_id'))->update(['phone' => $request->phone]);
        }

        $getcode = $this->getCode($phone);
        if($getcode['state'] == 0) {
            return response()->json(['state' => 0, 'error' => $getcode['error']]);
        } else {
            return response()->json(['state' => 500, 'error' => '发送验证码出错']);
        }

    }

    /**
     * 获取验证码
     * @param $phone
     * @return mixed
     */
    public function getCode($phone)
    {
        $rand = rand(100000, 999999);
        session(['bank_code' => $rand]);
        $appid = 1400037875;
        $appkey = "f98b59234537f5bd3ab6850e1e2c1e9d";
        $this->sms($appid, $appkey, $phone, 32739, [$rand, '绑定银行卡'], '获取验证码');

        return ['state' => 0, 'error' => '已发送验证码'];
    }

    /**
     * 验证验证码并保存数据
     * @param Request $request
     * @return mixed
     */
    public function checkCode(Request $request)
    {
        if(session('bank_code') == $request->code) {
            $data = [
                'bank_name' => session('bank_name'),
                'bank_number' => session('bank_number'),
                'bank_username' => session('bank_username')
            ];
            User::where('id', session('user_id'))->update($data);
            return response()->json(['state' => 0, 'error' => '添加账户成功', 'url' => route('index.user')]);
        }
    }
}