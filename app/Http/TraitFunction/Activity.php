<?php

namespace App\Http\TraitFunction;

use App\Models\Order;

trait Activity
{
    public function activity($product)
    {
        //判断正在下订单的用户是否已下过一次
        $where = [
            'user_id' => session('user_id'),
            'status' => 0,
            'product_id' => $product->id,
            'complete' => 1,
        ];
        $find_order = Order::where($where)->whereBetween('updated_at', [$product->tl_begin_time, $product->tl_end_time])->first();
        if ($find_order) {
            return false;
        }
    }

    //下订单前判断活动订单是否已售空（同时下单导致的情况）
    public function checkOrder($product, $type)
    {
        $where = [
            'product_id' => $product->id,
            'activity' => $type,
            'complete' => 1,
            'is_status' => 0
        ];
        $count = Order::where($where)->whereBetween('updated_at', [$product->tl_begin_time, $product->tl_end_time])->count();
        //免费名额
        if ( $type == 1 ) {
            if ( $count >= $product->tl_free_num ) {
                return [ 'state' => false, 'error' => '免费名额已售空' ];
            }
        } elseif ( $type == 2 ) {//第一折扣名额
            if ( $count >= $product->tl_one_num ) {
                return [ 'state' => false, 'error' => '折扣名额已售空' ];
            }
        } elseif ( $type == 3 ) {//第二折扣名额
            if ( $count >= $product->tl_two_num ) {
                return [ 'state' => false, 'error' => '折扣名额已售空' ];
            }
        }
    }

    /**
     * 判断活动期间折扣订单和免费订单是否购买完
     * @param $product
     * @return array
     */
    public function checkActivity($product)
    {
        //当免费和折扣数量不为空
        if($product->tl_free_num && $product->tl_one_num) {
            //判断免费订单
            $free_count = $this->orderCount($product->id, 1, $product->tl_begin_time, $product->tl_end_time);

            if ( $free_count >= $product->tl_free_num ) {
                //判断第一折扣订单
                $one_count = $this->orderCount($product->id, 2, $product->tl_begin_time, $product->tl_end_time);

                if ( $one_count >= $product->tl_one_num ) {
                    //判断是否有第二折扣价和数量
                    if ( $product->tl_two_off && $product->tl_two_num ) {
                        //判断第二折扣订单
                        $two_count = $this->orderCount($product->id, 3, $product->tl_begin_time, $product->tl_end_time);

                        if ( $two_count >= $product->tl_two_num ) {
                            //订单恢复活动价
                            return ['activity' => 4 ];
                        }
                        //订单价格变更为第二折扣价
                        return ['activity' => 3];
                    }
                    return ['activity' => 4 ];
                }
                //订单价格变更为折扣价
                return [ 'activity' => 2 ];
            }

            return ['activity' => 1];
        } elseif($product->tl_free_num && empty($product->tl_one_num)) {//当免费数量不为空，折扣数量为空
            //判断免费订单
            $free_count = $this->orderCount($product->id, 1, $product->tl_begin_time, $product->tl_end_time);

            if ( $free_count >= $product->tl_free_num ) {
                //订单价格回复原价
                return [ 'activity' => 4];
            }

            return ['activity' => 1];
        } elseif(empty($product->tl_free_num) && $product->tl_one_num) {//当免费数量为空，折扣数量不为空
            //判断折扣订单
            $one_count = $this->orderCount($product->id, 2, $product->tl_begin_time, $product->tl_end_time);

            if ( $one_count >= $product->tl_one_num ) {
                //判断是否有第二折扣价和数量
                if ( $product->tl_two_off && $product->tl_two_num ) {
                    //判断第二折扣订单
                    $two_count = $this->orderCount($product->id, 3, $product->tl_begin_time, $product->tl_end_time);

                    if ( $two_count >= $product->tl_two_num ) {
                        //订单恢复活动价
                        return [ 'activity' => 4 ];
                    }
                    //订单价格变更为第二折扣价
                    return [ 'activity' => 3 ];
                }
                //订单恢复活动价
                return [ 'activity' => 4 ];
            }

            return [ 'activity' => 2 ];
        }
    }

    //活动订单的数量
    public function orderCount($productid, $activity, $tl_begin_time, $tl_end_time)
    {
        $where = [
            'product_id' => $productid,
            'activity' => $activity,
            'complete' => 1,
            'is_status' => 0
        ];
        $ret = Order::where($where)->whereBetween('updated_at', [$tl_begin_time, $tl_end_time])->count();

        return $ret;
    }
}