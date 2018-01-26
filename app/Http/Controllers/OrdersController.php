<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/10 0010
 * Time: 下午 5:18
 */

namespace App\Http\Controllers;

use App\Http\TraitFunction\Activity;
use App\Models\Address;
use App\Models\Integral;
use App\Models\Order;
use App\Models\OrderAttr;
use App\Models\OrderPay;
use App\Models\Product;
use App\Models\Specification;
use App\Models\UseIntegral;
use App\Models\User;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    use Activity;

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
        $product = Product::find($request->product_id);
        if($product->stock == 0 || $product->shelves == 0) {
            return response()->json(['state' => 401, 'error' => '该商品已下架或售罄']);
        }

        //活动是否上线
        if(isset($request->activity) && strtotime($product->tl_begin_time) < time() && strtotime($product->tl_end_time) > time()){
            //检查未支付活动订单
//            $activity = $this->activity($product);
//            if(!$activity) {
//                return response()->json(['state' => 401, 'error' => '你有尚未付款的订单，不可重复下单！', 'url' => route('index.order_detail', $product->id)]);
//            }

            //检查活动订单售罄情况
            $check_order = $this->checkOrder($product, $request->activity);
            if(isset($check_order['state']) && !$check_order['state']) {
                return response()->json(['state' => 401, 'error' => $check_order['error']]);
            }
        }

        //检验是否购买过体验商品
        if($request->product_type == 3){
            $check_buy = Order::where(['product_id' => $request->product_id, 'product_type' => 3, 'status' => 1, 'is_status' => 0])->first();
            if($check_buy) {
                return response()->json(['state' => 401, 'error' => '您已体验过该商品']);
            }
        }

        $user = User::find(session('user_id'));
        //添加订单
        $order->fill($request->all());
        $order->number = self::orderNumber();
        $order->user_id = session('user_id');
        $order->product_price = $product->price;
        $order->p_id = $user->p_id;
        $order->dealer_id = $user->dealer_id;
        $order->save();

        //免费领取的其他属性
        if(strtotime($product->tl_begin_time) > time() || strtotime($product->tl_end_time) < time()) {
            if ( $request->product_type != 2 && empty($request->activity) ) {
                $attr_id = OrderAttr::insertGetId($request->only('spec', 'packing', 'postage'));
                $order->where('id', $order->id)->update([ 'order_attr_id' => $attr_id ]);
            }
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

        $order = $order->with('product', 'orderAttr.specs')->where('id', $order->id)->first();
//        $order_attr = Specification::where('id', $order->orderAttr['spec'])->first();

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

        if(strtotime($order->product->tl_begin_time) < time() && strtotime($order->product->tl_end_time) > time()) {
            return view('index.activity.order_pay', compact('order', 'address', 'extra_postage', 'integral', 'activity_check'));
        } else {
            return view('index.order_pay', compact('order', 'address', 'extra_postage', 'integral'));
        }
    }

    /**
     * 提交完整订单
     * @param Order $order
     * @param Request $request
     * @return mixed
     */
    public function orderPay( Request $request, Order $order )
    {
        if($order->product->stock == 0 || $order->product->shelves == 0) {
            return response()->json(['state' => 401, 'error' => '该商品已下架或售罄']);
        }
        if(strtotime($order->product->tl_begin_time) < time() && strtotime($order->product->tl_end_time) > time()) {
            //检查活动订单售罄情况
            $check_order = $this->checkOrder($order->product, $order->activity);
            if ( isset($check_order[ 'state' ]) && !$check_order[ 'state' ] ) {
                $order->delete();
                return response()->json([ 'state' => 401, 'error' => $check_order[ 'error' ], 'url' => route('index.product_details', $order->product->id) ]);
            }
        }

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
                    'url' => $click_pay['url']
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
                if($pay_type == 1) {
                    return [ 'state' => 0, 'url' => route('index.pay', [ 'type' => $pay_type, 'order_pay' => $add->id ]) ];
                } else {
                    return [ 'state' => 0, 'url' => route('index.alipay_ready', [ 'order' => $order_id, 'order_pay' => $add->id ]) ];
                }
            }
            return [ 'state' => 500 ];
        } else {
            if ( $add ) {
                if($pay_type == 1) {
                    return response()->json([ 'state' => 0, 'url' => route('index.pay', [ 'type' => $pay_type, 'order_pay' => $add->id ]) ]);
                } else {
                    return response()->json([ 'state' => 0, 'url' => route('index.alipay_ready', [ 'order' => $order_id, 'order_pay' => $add->id ]) ]);
                }
            }
            return response()->json(['state' => 500, 'error' => '支付出错']);
        }
    }

    public function orderResult( $state, Order $order )
    {
        return view('index.order_result', compact('state', 'order'));
    }
}