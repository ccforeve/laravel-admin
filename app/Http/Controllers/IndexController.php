<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/9 0009
 * Time: 下午 2:06
 */

namespace App\Http\Controllers;


use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class IndexController extends Controller
{
    public function index()
    {
        return view('index.index');
    }

    public function productDetails( Product $product, $pid = 0 )
    {
        if($pid) {
            session(['pid', $pid]);
        }

        if(Cache::has('product'.$product->id)) {
            $product = Cache::get('product'.$product->id);
        } else {
            $product = Product::with('spec')->where('id', $product->id)->first();
            Cache::put('product'.$product->id, $product, 60);
        }

        //滚动订单列表
        $orders = Order::with('product','user')->where(['status' => 1, 'is_status' => 0])->orderBy('pay_at', 'desc')->limit(20)->get();

        return view('index.product_details', compact('product', 'orders'));
    }

    /**
     * 免费领取或体验列表
     * @param $type
     * @return mixed
     */
    public function productList( $type )
    {
        $products = Product::where('type', 1)->get();

        return view('index.product_list', compact('products','type'));
    }

    /**
     * 判断用户是否购买套装
     * @param string $type
     * @return array
     */
    public function judge($type)
    {
        $uid = session('user_id');
        $is_buy_suit = Order::where(['user_id' => $uid, 'product_type' => 2, 'status' => 1])->first();
        if($type == 'experience'){
            if($is_buy_suit) {
                return response()->json(['state' => 401, 'error' => '您已经是会员，请去会员区免费领取', 'url' => route('index.product_list','free')]);
            }else {
                return response()->json(['state' => 0, 'url' => route('index.product_list', 'experience')]);
            }
        } else if($type == 'free'){
            if($is_buy_suit){
                return response()->json(['state' => 0, 'url' => route('index.product_list', 'free')]);
            }else{
                return response()->json(['state' => 401, 'error' => '请先购买套装后再免费领取', 'url' => route('index.product_details', ['product'=>1,'pid'=>session('pid')])]);
            }
        }
    }


}