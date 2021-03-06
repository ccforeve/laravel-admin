<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/16 0016
 * Time: 下午 4:41
 */

namespace App\Http\Controllers;


use App\Models\Integral;
use App\Models\UseIntegral;
use App\Models\User;
use Carbon\Carbon;
use EasyWeChat\Factory;
use Illuminate\Http\Request;

class ExtensionController extends Controller
{
    /**
     * 推广首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $user_id = session('user_id');
        $suit = app(Integral::class)->integral($user_id, 2);
        $free = app(Integral::class)->integral($user_id, 1);
        $user = User::find($user_id);

        return view('index.reward', compact('user','free','suit'));
    }

    /**
     * 推广列表
     * @param int $type
     * @param int $status
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function rewardList( $type = 0, $status = 0 )
    {
        $where['user_id'] = session('user_id');
        if($type) $where['type'] = $type;
        if($status) $where['status'] = $status;
        $reward_list = Integral::with('order.user','order.product','order.orderAttr.specs')->where($where)->get();

        return view('index.reward_list', compact('reward_list'));
    }

    /**
     * 推广海报
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function extensionPage(User $user)
    {
        $app = Factory::officialAccount(config('wechat'));
//        $result = $app->qrcode->temporary($user->id, 6 * 24 * 3600);
//        $qrcode_url = $app->qrcode->url($$result['ticket']);
        $qrcode_url = '';

        return view('index.extension_page', compact('user','qrcode_url'));
    }

    /**
     * 积分兑换页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function exchange(  )
    {
        $suit = app(Integral::class)->integral(session('user_id'), 2);
        $free = app(Integral::class)->integral(session('user_id'), 1);
        $exchange_time = User::where('id', session('user_id'))->value('exchange_time');

        return view('index.exchange', compact('suit', 'free', 'exchange_time'));
    }

    /**
     * 积分提现操作
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function exchangeOperation( Request $request )
    {
        $user_id = session('user_id');
        //--------------判断是否已绑定银行卡----------------
        $user = User::find($user_id);
        if(empty($user->bank_name) && empty($user->bank_number)){
            return response()->json(['state' => 401, 'error' => '你未绑定银行卡或支付宝', 'url' => route('index.card_bind')]);
        }
        //--------------判断是否已绑定银行卡END-------------
        $data = $request->all();
        $data['use_integral'] = $data['integral'] > 200 ? 200 : $data['integral'];

        if($data['type']==1){
            if(Carbon::now()->lt(Carbon::parse($user->exchange_time))){
                return response()->json(['state' => 401, 'error' => '未到兑换时间']);
            }
            //免费领取积分时间增加一个月
            User::where('id', $user_id)->update(['exchange_time' => Carbon::now()->addMonth()]);
        }

        $data['user_id'] = $user_id;
        $data['mean'] = 2;
        $data['status'] = 1;
        UseIntegral::create($data);

        return response()->json(['state' => 0, 'error' => '兑换完成']);
    }

    /**
     * 积分兑换列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function record()
    {
        $use_integrals = UseIntegral::where('user_id', session('user_id'))->get();

        return view('index.record_list', compact('use_integrals'));
    }
}