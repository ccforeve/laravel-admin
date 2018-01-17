<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/13 0013
 * Time: 下午 1:29
 */

namespace App\Http\Controllers;


use App\Models\Order;
use App\Models\OrderPay;
use App\Models\Product;
use App\Models\User;
use EasyWeChat\Factory;
use Yansongda\Pay\Pay;

class PayController extends Controller
{
    /**
     * 支付宝准备支付页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function aliReady()
    {
        return view('index.alipay_ready');
    }

    /**
     * 支付
     * @param $type
     * @param Order $order
     * @param OrderPay $orderPay
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function pay( $type, Order $order, OrderPay $orderPay )
    {
        $body = Product::where('id', $order->product_id)->value('name');
        //微信支付
        if($type == 1) {
            $config_biz = [
                'body' => $body,
                'out_trade_no' => $orderPay->number,
                'total_fee' => $order->pay_price,
                'notify_url' => route('wechat_notify'), // 支付结果通知网址，如果不设置则会使用配置里的默认地址
                'trade_type' => 'JSAPI',
                'openid' => User::where('id', $order->user_id)->value('openid'),
            ];
            $this->wechatPay($config_biz);
        } elseif($type == 2) {//支付宝支付
            $config_biz = [
                'out_trade_no' => $orderPay->number,    // 订单号
                'total_amount' => $order->pay_price,    // 订单金额，单位：元
                'subject' => $body,                     // 订单商品标题
            ];
            $this->aliPay($config_biz);
        }
    }

    /**
     * 微信公众号支付
     * @param $post_data
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function wechatPay($post_data)
    {
        $app = Factory::payment(config('wechat'));
        $app->order->unify($post_data);
    }

    /**
     * 支付宝手机端支付
     * @param $post_data
     * @return mixed
     */
    public function aliPay($post_data)
    {
        $alipay = Pay::alipay(config('alipay.alipay'));
        return $alipay->wap($post_data);
    }
}