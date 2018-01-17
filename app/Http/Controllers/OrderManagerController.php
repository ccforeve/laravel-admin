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

        return view('index.order_detail', compact('order', 'logistics'));
    }
}