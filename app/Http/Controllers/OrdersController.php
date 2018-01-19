<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/10 0010
 * Time: 下午 5:18
 */

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Integral;
use App\Models\Order;
use App\Models\OrderAttr;
use App\Models\OrderPay;
use App\Models\OrderRefund;
use App\Models\Product;
use App\Models\Specification;
use App\Models\UseIntegral;
use App\Models\User;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    private static function orderNumber(){
        return date('YmdHis') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8) . rand(10000, 99999);
    }

    /**
     * 下订单
     * @param Request $request
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function order( Request $request, Order $order )
    {
        //检验是否购买过体验商品
        if($request->product_type == 3){
            $check_buy = Order::where(['product_id' => $request->product_id, 'product_type' => 3, 'status' => 1, 'is_status' => 0])->first();
            if($check_buy) {
                return response()->json(['state' => 401, 'error' => '您已体验过该商品']);
            }
        }

        $user = User::find(session('user_id'));
        //添加订单
        $product = Product::find($request->product_id);
        $order->fill($request->all());
        $order->number = self::orderNumber();
        $order->user_id = session('user_id');
        $order->product_price = $product->price;
        $order->p_id = $user->p_id;
        $order->dealer_id = $user->dealer_id;
        $order->save();

        //免费领取的其他属性
        if($request->product_type != 2) {
            $attr_id = OrderAttr::insertGetId($request->only('spec', 'packing', 'postage'));
            $order->where('id', $order->id)->update(['order_attr_id' => $attr_id]);
        }

        return response()->json(['state' => 0, 'error' => '提交成功', 'url' => route('index.order_ready_pay', $order->id)]);
    }

    /**
     * 订单准备支付页面
     * @param Order $order
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function orderReadyPay( Order $order )
    {
        session(['order_id' => $order->id]);

        $order = $order->with('product', 'orderAttr')->where('id', $order->id)->first();
        $order_attr = Specification::where('id', $order->orderAttr['spec'])->first();

        //用户套装积分
        $integral = app(Integral::class)->integral(session('user_id'), 2);

        //查看地址并判断是否属于偏远地区
        $extra_postage = 0;
        $aid = User::where('id', session('user_id'))->value('address_id');
        $address = Address::find($aid);
        if($address){
            $province = strstr($address->province, ' ', true);
            if(in_array($province ,config('system.postage_area'))) {
                $extra_postage = 10;
            }
        }

        return view('index.order_pay', compact('order', 'order_attr', 'address', 'extra_postage', 'integral'));
    }

    /**
     * 提交完整订单
     * @param Order $order
     * @param Request $request
     * @return mixed
     */
    public function orderPay( Request $request, Order $order )
    {
        $data = $request->all();
        $data['complete'] = 1;
        $update = $order->update($data);
        if($update) {
            //偏远地区加邮费
            if(isset($request->postarea)) {
                OrderAttr::where('id', $order->order_attr_id)->update(['postage_area' => $request->postarea]);
            }

            //使用积分
            if(isset($request->use_integral)) {
                $data = ['user_id' => session('user_id'),'mean' => 1,'order_id'=>$order->id,'use_integral'=>$request->use_integral,'type'=>2];
                UseIntegral::create($data);
            }

            //每点击一次支付就生成一条支付记录
            $click_pay = $this->clickPay($order->id ,$request->pay_type, 1);
            if($click_pay['state'] == 0) {
                $ret = [
                    'state' => 0,
                    'error' => '提交成功',
                    'url' => route('index.pay', ['type' => $request->pay_type, 'order' => $order->id, 'order_pay' => $click_pay['data']])
                ];
                return response()->json($ret);
            } else {
                return response()->json(['state' => 500, 'error' => '提交失败']);
            }

        }
    }

    /**
     * 每点击一次支付就生成一条支付记录
     * @param $order_id
     * @param $pay_type
     * @param int $first_pay
     * @return mixed
     */
    public function clickPay($order_id, $pay_type, $first_pay = 0)
    {
        $error = app(Order::class)->orderError($order_id);
        if(!$error['state']) {
            if($first_pay) return ['state' => 0, 'error' => $error['error']];
            else return response()->json(['state' => 0, 'error' => $error['error']]);
        }
        $pay_data = [
            'number' => self::orderNumber(),
            'user_id' => session('user_id'),
            'order_id' => $order_id,
            'mode' => $pay_type
        ];
        $add = OrderPay::create($pay_data);
        if($first_pay) {
            if ( $add ) {
                return [ 'state' => 0, 'data' => $add->id ];
            }
            return [ 'state' => 500 ];
        } else {
            if ( $add ) {
                if($pay_type == 1) {
                    return response()->json([ 'state' => 0, 'url' => route('index.pay', [ 'type' => $pay_type, 'order' => $order_id, 'order_pay' => $add ]) ]);
                } else {
                    return response()->json([ 'state' => 0, 'url' => route('index.alipay_ready', [ 'order' => $order_id, 'order_pay' => $add ]) ]);
                }
            }
            return response()->json(['state' => 500, 'error' => '支付出错']);
        }
    }


    public function orderOperation(Order $order, $type, $msg='')
    {
        if($type == 4){ //确认收货
            $order->update(['comfirm' => 2]);
            //经销商和推广人获得的积分变为确认积分
            Integral::where('order_id', $order->id)->update(['status' => 2]);
            //售后订单更改为已完成

            //积分状态修改
            app(UseIntegral::class)->status($order->use_integral, $order->id, 1);
        } elseif($type == 2 || $type == 3) {//申请退款
            //经销商和推广人获得的积分变为退款积分
            Integral::where('order_id', $order->id)->update(['status' => 3]);
            //添加退款申请时间
            $refund = OrderRefund::create(['apply_at' => date('Y-m-d H:i:s')]);
            $order->where('id', $order->id)->update(['order_refund_id' => $refund->id, 'is_status' => 1]);
            //积分状态修改
            app(UseIntegral::class)->status($order->use_integral, $order->id, 2);
        } elseif($type == 1) {//删除订单
            $order->delete();
            //删除积分
            app(UseIntegral::class)->delete();
            //积分状态修改
//            app(UseIntegral::class)->status($order->use_integral, $order->id, 2);
        } elseif($type == 5) {//取消订单
            $order->where('id', $order->id)->update(['is_status' => 3]);
            //积分状态修改
            app(UseIntegral::class)->status($order->use_integral, $order->id, 2);
        }

        return response()->json(['status' => 0, 'error' => $msg.'完成']);
    }
}