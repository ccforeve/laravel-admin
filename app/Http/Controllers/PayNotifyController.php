<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/13 0013
 * Time: 下午 3:00
 */

namespace App\Http\Controllers;


use App\Http\Controllers\TraitFunction\Notice;
use App\Models\Address;
use App\Models\Integral;
use App\Models\Order;
use App\Models\OrderPay;
use App\Models\Product;
use App\Models\UseIntegral;
use App\Models\User;
use EasyWeChat\Factory;
use Illuminate\Http\Request;
use Yansongda\Pay\Pay;

class PayNotifyController extends Controller
{
    use Notice;

    /**
     * 微信支付异步回调通知
     * @return mixed
     */
    public function wechatNotify()
    {
        $app = Factory::officialAccount(config('wechat'));
        $response = $app->handlePaidNotify(function ($message, $fail) {
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
            $order_id = OrderPay::where('number', $message['out_trade_no'])->value('order_id');
            $order = Order::find($order_id);

            // 如果订单不存在
            if (!$order) {
                $fail('Order not exist.'); // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }

            // 如果订单存在
            // 检查订单是否已经更新过支付状态
            if ($order->pay_at) { // 假设订单字段“支付时间”不为空代表已经支付
                return true; // 已经支付成功了就不再更新了
            }

            // 用户是否支付成功
            if ($message['result_code'] === 'SUCCESS') {
                $this->commonNotify($order, $message['out_trade_no'], $message['transaction_id']);

                //发送微信模板消息
                $template_data = [
                    'touser' => User::where('id', $order->user_id)->value('openid'),
                    'template_id' => 'kRHujmBG4w9tgHdaj2iH7_CREQ9cUCxrx_ikbE1ojK4',
                    'url' => 'http://www.meifusp.com',
                    'data' => [
                        "first"     =>  "恭喜您支付成功！我们将尽快为您打包商品，准备发货。",
                        "keyword1"  =>  Product::where('id', $order->product_id)->value('name'),
                        "keyword2"  =>  "{$order->pay_price}元",
                        "keyword3"  =>  date('Y-m-d H:i:s'),
                        "remark"    =>  "如有任何问题，请您联系在线客服。感谢您对我们的信赖与支持，期待您下次光临！",
                    ]
                ];
                $app = Factory::officialAccount(config('wechat'));
                $app->template_message->send($template_data);
            }

            return true; // 返回处理完成
        });

        return $response->send(); // Laravel 里请使用：return $response;
    }

    /**
     * 支付宝支付异步回调通知
     * @param Request $request
     */
    public function aliNotify(Request $request)
    {
        $pay = new Pay(config('alipay.alipay'));
        $verify = $pay->driver('alipay')->gateway()->verify($request->all());
        if($verify) {
            $order_id = OrderPay::where('number', $request->out_trade_no)->value('order_id');
            $order = Order::find($order_id);
            $this->commonNotify($order, $request->out_trade_no, $request->trade_no);
        }

    }

    /**
     * 支付宝支付同步通知
     */
    public function aliRetrun()
    {

    }

    /**
     * 支付异步通知处理封装
     * @param $order
     * @param $out_trade_no
     * @param $transaction_id
     */
    public function commonNotify($order, $out_trade_no, $transaction_id)
    {
        OrderPay::where('number', $out_trade_no)->update(['status' => 1, 'trade_no' => $transaction_id]);
        // 订单支付状态修改为已经支付状态
        $order->pay_at = date('Y-m-d H:i:s'); // 更新支付时间为当前时间
        $order->status = 1;
        $order->save(); // 保存订单
        //商品数量减一
        Product::where('id', $order->product_id)->decrement('stock');
        //修改使用积分状态
        UseIntegral::where('order_id', $order->id)->update(['status' => 1]);
        //经销商或普通用户获得积分
        $p_dealer = User::where('id', $order->dealer_id)->first();
        if($order->product_type == 1) {
            //免费类型只有经销商能获得1积分
            if($order->dealer_id) {
                $integral = [ 'order_id' =>  $order->id, 'user_id'  =>  $order->dealer_id, 'integral'  =>  1, 'type'  =>  1 ];
                Integral::create($integral);
                if($p_dealer->dealer_id){
                    $integral = [ 'order_id' =>  $order->id, 'user_id'  =>  $p_dealer->dealer_id, 'integral'  =>  1, 'type'  =>  1 ];
                    Integral::create($integral);
                }
            }
            if($order->p_id) {
                User::where('id', $order->p_id)->update([ 'is_extension' => 1 ]);
            }
        } elseif($order->product_id == 2) {
            //经销商获得套装类型积分
            if($order->dealer_id){
                //经销商比例
                if(!empty($p_dealer->scale)) $scale = $p_dealer->scale / 100;
                else $scale = 0.3;

                $integral = [
                    'order_id' =>  $order->id, 'user_id'  =>  $order->dealer_id, 'integral'  =>  ceil($order->pay_price * $scale), 'type'  =>  2
                ];
                Integral::create($integral);
                if($p_dealer->dealer_id){
                    $integral = [
                        'order_id' =>  $order->id, 'user_id'  =>  $p_dealer->dealer_id, 'integral'  =>  ceil($order->pay_price * 0.05), 'type'  =>  2
                    ];
                    Integral::create($integral);
                }
            }
        }

        //发送短信通知
        $address = Address::where('id', $order->address_id)->first();
        $this->sms($address->phone, 71275, [], '购买成功通知');
    }
}