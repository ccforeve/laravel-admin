<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/15 0015
 * Time: 下午 2:24
 */

namespace App\Http\Controllers;

use App\Classes\Kd\Kuaidi;
use App\Models\Order;
use App\Models\UseIntegral;
use Illuminate\Http\Request;

class OrderManagerController extends Controller
{
    /**
     *订单列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function orderList(Request $request)
    {
        $where = ['user_id' => session('user_id'), 'complete' => 1];
        if(isset($request->status) && $request->status != 2) {
            $where = array_merge($where, [ 'status' => $request->status ]);
            $where = array_merge($where, [ 'is_status' => 0 ]);
        }
        if($request->confirm) {
            $where = array_merge($where, [ 'confirm' => $request->confirm ]);
            $where = array_merge($where, [ 'is_status' => 0 ]);
        }
        $order_lists = Order::with('product', 'orderAttr.specs')->where($where)->orderBy('created_at', 'desc')->get();

        return view('index.order_list', compact('order_lists'));
    }

    /**
     * 订单详情
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function orderDetail( $id )
    {
        $order = Order::with('product','address','orderAttr.specs','logistic')->where('id', $id)->first();
        $logistics = '';
        if($order->logistic) {
            $kd = new Kuaidi();
            $logistics = json_decode($kd->getTransport($order->logistic->express_name, $order->logistic->express_number), true);
        }

        if($order->activity) {
            return view('index.activity.order_detail', compact('order', 'logistics'));
        }

        return view('index.order_detail', compact('order', 'logistics'));
    }

    /**
     * 订单列表按钮操作
     * @param Order $order
     * @param $type
     * @param string $msg
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
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